<?php

namespace Swis\JsonApi\Server\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Swis\JsonApi\Server\Traits\HandlesRelationships;

class BaseApiResource extends Resource
{
    use HandlesRelationships;

    protected $includes = [];
    protected $includedRelationships = [];
    protected $jsonApiModel;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->jsonApiModel = new JsonApiResource();
        $this->setValues();
        $this->setValues();
    }

    public function toArray($request, $isCollection = false)
    {
        $response = [];
        $wrap = false;
        $isMasterResource = $this->findMasterResource($request->getPathInfo()) == $this->getResourceType();
        if (!$isCollection && !$wrap && $isMasterResource) {
            $wrap = true;
            $response['data'] = [];
            $this->jsonApiModel->setIncluded($this->getIncludedRelationships($request));
        }

        return $this->mapToJsonApi($response, $wrap);
    }

    public function setValues()
    {
        if (!$this->resource) {
            return;
        }
        $this->resource->addHidden($this->resource->getKeyName());


        $this->jsonApiModel->setId((string) $this->resource->getKey());
        $this->jsonApiModel->setType($this->getResourceType());
        $this->jsonApiModel->setAttributes($this->resource->attributesToArray());
        $this->jsonApiModel->setRelationships($this->relationships());
        $this->jsonApiModel->setLinks($this->getLinks());
        $this->translateAttributes();
        $this->setExtraValues();
        $this->filterTypeFromAttributes();
    }

    public function mapToJsonApi($response, bool $wrap)
    {
        $jsonApiArray = [
            'id' => $this->jsonApiModel->getId(),
            'type' => $this->jsonApiModel->getType(),
            'attributes' => $this->jsonApiModel->getAttributes(),
            'links' => $this->jsonApiModel->getLinks(),
            'relationships' => $this->jsonApiModel->getRelationships(),
            'included' => $this->jsonApiModel->getIncluded(),
        ];

        foreach ($jsonApiArray as $key => $value) {
            if ($wrap && 'included' != $key) {
                $response['data'] = $this->addToResponse($response['data'], $key, $value);
                continue;
            }

            $response = $this->addToResponse($response, $key, $value);
        }

        return $response;
    }

    protected function addToResponse($response, $key, $value)
    {
        if (!isset($value) || empty($value)) {
            return $response;
        }
        $response[$key] = $value;

        return $response;
    }

    protected function translateAttributes()
    {
        if (!$this->resource->translatedAttributes) {
            return;
        }

        $attributes = $this->jsonApiModel->getAttributes();

        foreach ($this->resource->translatedAttributes as $key => $translation) {
            if ($this->resource->$translation == '') {// temp while there are still empty values in translations table
                continue;
            }

            $attributes[$translation] = $this->resource->$translation;
        }

        $this->jsonApiModel->setAttributes($attributes);
    }

    protected function setExtraValues()
    {
        if (method_exists($this->resource, 'getExtraValues')) {
            $this->jsonApiModel->setAttributes($this->jsonApiModel->getAttributes() + $this->getExtraValues());
        }
    }

    protected function findMasterResource($str)
    {
        if (empty($str)) {
            return;
        }

        $masterResource = substr($str, strrpos($str, '/') + 1);

        if (is_numeric($masterResource)) {
            $str = str_replace('/'.$masterResource, '', $str);
            $masterResource = $this->findMasterResource($str);
        }

        return $masterResource;
    }

    /*    protected function getPivotAttributes()
        {
            $attributes = [];

            if ($this->resource->pivot) {
                $attributes = $this->resource->pivot->attributesToArray();
                if (array_key_exists('id', $attributes)) {
                    $attributes['pivot_id'] = $attributes['id'];
                    unset($attributes['id']);
                }
            }
            return $attributes;
        }*/

    protected function getLinks()
    {
        return [
            'self' => env('API_URL').'/'.$this->getResourceType().'/'.$this->resource->getKey(),
        ];
    }

    protected function relationships()
    {
        $relationships = $this->getRelationships($this->resource);
        $this->jsonApiModel->setAttributes($this->jsonApiModel->getAttributes() + ['available_relationships' => $relationships]);
        $relationshipsIdentifiers = [];


        foreach ($relationships as $relationship) {
            if (!config('laravel_api.loadAllJsonApiRelationships') && !$this->resource->relationLoaded($relationship)) {
                continue;
            }

            $data = $this->resource->$relationship;
            if (0 == count($data)) {
                continue;
            }

            $relationshipData = $this->getRelationshipData($data);

            if (empty($relationshipData) || !$relationshipData) {
                continue;
            }

            $relationshipsIdentifiers[$relationship] = ['data' => $relationshipData];
        }
        return $relationshipsIdentifiers;
    }

    protected function getRelationshipData($data)
    {
        $relationshipData = [];

        if ($data instanceof Collection) {
            $relationshipData = IdentifierResource::collection($data);
            foreach ($relationshipData as $key => $relation) {
                if (!$this->checkIfDataIsSet($relation)) {
                    unset($relationshipData[$key]);
                }
            }

            if ($relationshipData->toArray(true) == []) {
                $relationshipData = [];
            }
        } elseif ($data instanceof Model) {
            $relationshipData = IdentifierResource::make($data);
            $this->checkIfDataIsSet($relationshipData) ?: $relationshipData = [];
        }

        return $relationshipData;
    }

    protected function filterTypeFromAttributes()
    {
        if (!array_key_exists('type', $this->jsonApiModel->getAttributes())) {
            return;
        }

        if (!method_exists($this->resource, 'getTypeAlias')) {
            throw new \Exception('Your model lacks the method getTypeAlias');
        }

        $this->jsonApiModel->changeTypeInAttributes($this->getTypeAlias());
    }

    protected function checkIfDataIsSet($relationshipData): bool
    {
        return isset($relationshipData->resource->id);
    }

    protected function getIncludedRelationships(Request $request)
    {
        $this->includes = explode(',', $request->get('include', null));
        if (null == $this->includes) {
            return [];
        }
        $relations = $this->includeRelationships($this->resource, $this->includes);

        return $relations;
    }

    protected function getResourceType()
    {
        $resourceClass = class_basename($this->resource);
        $resourcePlural = str_plural($resourceClass);

        // Converts camelcase to dash
        $lowerCaseResourceType = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $resourcePlural));

        return $lowerCaseResourceType;
    }

    /**
     * Create new anonymous resource collection.
     *
     * @param mixed $resource
     *
     * @return mixed
     */
    public static function collection($resource)
    {
        return new class($resource, get_called_class()) extends AnonymousResourceCollection {
            /**
             * @var string
             */
            public $collects;

            /**
             * Create a new anonymous resource collection.
             *
             * @param mixed  $resource
             * @param string $collects
             */
            public function __construct($resource, $collects)
            {
                $this->collects = $collects;
                parent::__construct($resource);
            }
        };
    }
}

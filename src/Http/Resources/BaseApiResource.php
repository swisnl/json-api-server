<?php

namespace Swis\LaravelApi\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Swis\LaravelApi\Traits\HandlesRelationships;

class BaseApiResource extends Resource
{
    use HandlesRelationships;

    protected $includes = [];
    protected $includedRelationships = [];

    /**
     * Transform the resource into an array.
     *
     * @param mixed $request
     * @param bool $isCollection
     *
     * @return array
     *
     * @internal param $ \Illuminate\Http\Request
     */
    public function toArray($request, $isCollection = false)
    {
        $response = [];

        if (is_string($request) || $isCollection) {
            return $this->mapToJsonApi($response);
        }

        $masterResource = $this->findMasterResource($request->getPathInfo());

        if ($masterResource == $this->getResourceType()) {
            $response['data'] = $this->mapToJsonApi($response);

            $this->includedRelationships = $this->getIncludedRelationships($request);
            empty($this->includedRelationships) ?: $response['included'] = $this->includedRelationships;

            return $response;
        }

        return $this->mapToJsonApi($response);
    }

    public function mapToJsonApi($response)
    {
        if (!isset($this->id)) {
            return;
        }

        $this->resource->addHidden($this->resource->getKeyName());
        $pivotAttributes = $this->getPivotAttributes();
        $relationships = $this->relationships();

        $response['type'] = $this->getResourceType();
        $response[$this->getKeyName()] = (string)$this->resource->getKey();

        $response['attributes'] = $this->filterTypeFromAttributes();

        if (method_exists($this->resource, 'getExtraValues')) {
            $response['attributes'] += $this->getExtraValues();
        }

        if ($this->resource->translatedAttributes) {
            foreach ($this->resource->translatedAttributes as $translation) {
                if ($this->resource->$translation == '') {// temp while there are still empty values in translations table
                    continue;
                }

                $response['attributes'][$translation] = $this->resource->$translation;
            }
        }

        $pivotAttributes === [] ?: $response['attributes']['pivot'] = $pivotAttributes;
        $relationships === [] ?: $response['relationships'] = $relationships;

        $response['links'] = $this->getLinks();

        return $response;
    }

    protected function findMasterResource($str)
    {
        if (empty($str)) {
            return;
        }

        $masterResource = substr($str, strrpos($str, '/') + 1);

        if (is_numeric($masterResource)) {
            $str = str_replace('/' . $masterResource, '', $str);
            $masterResource = $this->findMasterResource($str);
        }

        return $masterResource;
    }

    protected function filterTypeFromAttributes()
    {
        $attributes = $this->attributesToArray();

        if (!isset($attributes['type'])) {
            return $attributes;
        }

        if (!method_exists($this->resource, 'getTypeAlias')) {
            throw new \Exception('Your model lacks the method getTypeAlias');
        }

        $attributes[$this->getTypeAlias()] = $attributes['type'];
        unset($attributes['type']);

        return $attributes;
    }

    protected function getPivotAttributes()
    {
        $attributes = [];

        if ($this->resource->pivot) {
            $attributes = $this->resource->pivot->attributesToArray();
        }

        return $attributes;
    }

    protected function getLinks()
    {
        return [
            'self' => env('API_URL') . '/' . $this->getResourceType() . '/' . $this->resource->getKey(),
        ];
    }

    protected function relationships()
    {
        $relationships = $this->getRelationships($this->resource);
        $relationshipsIdentifiers = [];

        foreach ($relationships as $relationship) {
            if (!config('laravel_api.loadAllJsonApiRelationships') && !$this->resource->relationLoaded($relationship)) {
                continue;
            }

            $data = $this->resource->$relationship;
            $relationshipData = [];

            if (0 == count($data)) {
                continue;
            }

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

            empty($relationshipData) ?: $relationshipsIdentifiers[$relationship] = ['data' => $relationshipData];
        }

        return $relationshipsIdentifiers;
    }

    protected function checkIfDataIsSet($relationshipData): bool
    {
        return isset($relationshipData->resource->id);
    }

    protected function getIncludedRelationships($request)
    {
        if (!$request instanceof Request) {
            return [];
        }

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
        return new class($resource, get_called_class()) extends AnonymousResourceCollection
        {
            /**
             * @var string
             */
            public $collects;

            /**
             * Create a new anonymous resource collection.
             *
             * @param mixed $resource
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

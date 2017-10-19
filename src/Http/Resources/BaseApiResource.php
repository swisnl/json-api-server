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
     * @param bool  $isCollection
     *
     * @return array
     *
     * @internal param $ \Illuminate\Http\Request
     */
    public function toArray($request, $isCollection = false)
    {
        $response = [];

        if (!$isCollection && !is_string($request) && strpos($request->getPathInfo(), $this->getResourceType())) {
            $response['data'] = $this->mapToJsonApi($response);

            $this->includedRelationships = $this->getIncludedRelationships($request);
            $this->includedRelationships === [] ?: $response['included'] = $this->includedRelationships;

            return $response;
        }

        return $this->mapToJsonApi($response);
    }

    public function mapToJsonApi($response)
    {
        $this->resource->addHidden($this->resource->getKeyName());
        $pivotAttributes = $this->getPivotAttributes();

        $response['type'] = $this->getResourceType();
        $response[$this->getKeyName()] = (string) $this->resource->getKey();

        $response['attributes'] = $this->attributesToArray();
        $pivotAttributes === [] ?: $response['attributes']['pivot'] = $pivotAttributes;

        $response['relationships'] = $this->relationships();
        $response['links'] = $this->getLinks();

        return $response;
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
            'self' => env('API_URL').'/'.$this->getResourceType().'/'.$this->resource->getKey(),
        ];
    }

    protected function relationships()
    {
        $relationships = $this->getRelationships($this->resource);
        $relationshipsIdentifiers = [];

        foreach ($relationships as $relationship) {
            $data = $this->resource->$relationship;

            if (0 == count($data)) {
                continue;
            }

            if ($data instanceof Collection) {
                $relationshipsIdentifiers[$relationship] = [
                    'data' => IdentifierResource::collection($data),
                ];
                continue;
            } elseif ($data instanceof Model) {
                $relationshipsIdentifiers[$relationship] = [
                    'data' => IdentifierResource::make($data),
                ];
            }
        }

        return $relationshipsIdentifiers;
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
        $lowerCaseResourceType = strtolower($resourcePlural);

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

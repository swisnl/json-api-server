<?php

namespace Swis\LaravelApi\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Swis\LaravelApi\Traits\HandlesRelationships;

class BaseApiResource extends Resource
{
    use HandlesRelationships;

    protected $includes = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $includedRelationships = $this->getIncludedRelationships($request);
        $this->resource->addHidden($this->resource->getKeyName());

        $response = [];
        $response['type'] = $this->getResourceType();
        $response[$this->getKeyName()] = (string) $this->resource->getKey();
        $response['attributes'] = $this->attributesToArray();
        $response['relationships'] = $this->relationships();
        $response['links'] = [
            'self' => env('API_URL').'/'.$this->getResourceType().'/'.$this->resource->getKey(),
        ];
        $includedRelationships === [] ?: $response['included'] = $includedRelationships;

        return $response;
    }

    protected function relationships()
    {
        $relationships = $this->getRelationships($this->resource);
        $relationshipsIdentifiers = [];

        foreach ($relationships as $relationship) {
            $data = $this->resource->$relationship;

            if ($data instanceof Collection) {
                $relationshipsIdentifiers[$relationship] = [
                    'data' => IdentifierResource::collection($data),
                ];
                continue;
            }

            $relationshipsIdentifiers[$relationship] = [
                'data' => IdentifierResource::make($data),
            ];
        }

        return $relationshipsIdentifiers;
    }

    protected function getIncludedRelationships($request)
    {
        if (!$request instanceof Request || $this->includes == []) {
            return [];
        }

        $relations = $this->includeRelationships($this->resource, $this->includes);

        return $relations;
    }

    public function withIncludedRelationships($request)
    {
        $this->includes = explode(',', $request->get('include', null));

        return $this;
    }

    protected function getResourceType()
    {
        $resourceClass = class_basename($this->resource);
        $resourcePlural = str_plural($resourceClass);
        $lowerCaseResourceType = strtolower($resourcePlural);

        return $lowerCaseResourceType;
    }
}

<?php

namespace Swis\LaravelApi\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Swis\LaravelApi\Traits\HandlesRelationships;

class BaseApiCollectionResource extends ResourceCollection
{
    use HandlesRelationships;

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
        $items = BaseApiResource::collection($this->collection);
        $includedRelationships = $this->getIncludedRelationships($items, $request);
        $response = [];

        $response['data'] = $items;
        $includedRelationships === [] ?: $response['included'] = $includedRelationships;

        return $response;
    }

    protected function getIncludedRelationships($items, $request)
    {
        if (!$request instanceof Request) {
            return [];
        }

        $includes = $includes = explode(',', $request->get('include', null));

        $relations = $this->includeCollectionRelationships($items, $includes);

        return $relations;
    }
}

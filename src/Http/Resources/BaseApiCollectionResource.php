<?php

namespace Swis\JsonApi\Server\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Swis\JsonApi\Server\Traits\HandlesRelationships;

class BaseApiCollectionResource extends ResourceCollection
{
    use HandlesRelationships;

    /**
     * Transform the resource into an array.
     *
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
        empty($includedRelationships) ?: $response['included'] = $includedRelationships;
        response($items);
        return $response;
    }

    protected function getIncludedRelationships($items, $request)
    {
        if (!$request instanceof Request) {
            return [];
        }

        $includes = explode(',', $request->get('include', null));
        if ($includes === []) {
            return [];
        }
        $relations = $this->includeCollectionRelationships($items, $includes);
        return $relations;
    }
}

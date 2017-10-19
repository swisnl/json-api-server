<?php

namespace Swis\LaravelApi\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AnonymousResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into a JSON array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map->toArray($request, true)->all();
    }
}

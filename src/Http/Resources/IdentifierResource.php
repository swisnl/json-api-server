<?php

namespace Swis\LaravelApi\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class IdentifierResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => $this->getResourceType(),
            $this->resource->getKeyName() => (string)$this->resource->getKey(),
        ];
    }

    protected function getResourceType()
    {
        $resourceClass = class_basename($this->resource);
        $resourcePlural = str_plural($resourceClass);
        $lowerCaseResourceType = strtolower($resourcePlural);
        return $lowerCaseResourceType;
    }
}
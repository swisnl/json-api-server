<?php

namespace Swis\JsonApi\Server\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class IdentifierResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => $this->getResourceType(),
            $this->resource->getKeyName() => (string) $this->resource->getKey(),
        ];
    }

    protected function getResourceType()
    {
        $resourceClass = class_basename($this->resource);
        $resourcePlural = str_plural($resourceClass);
        // Converts camelcase to dash
        $lowerCaseResourceType = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $resourcePlural));

        return $lowerCaseResourceType;
    }
}

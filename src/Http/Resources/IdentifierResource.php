<?php

namespace Swis\JsonApi\Server\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class IdentifierResource extends JsonResource
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
        $resourcePlural = Str::plural($resourceClass);
        // Converts camelcase to dash
        $lowerCaseResourceType = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $resourcePlural));

        return $lowerCaseResourceType;
    }
}

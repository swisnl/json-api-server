<?php

namespace Swis\JsonApi\Server\Http\Resources;

class JsonApiResource
{
    protected $type;
    protected $id;
    protected $attributes;
    protected $relationships;
    protected $links;
    protected $included;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function setRelationships($relationships)
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }

    public function getIncluded()
    {
        return $this->included;
    }

    public function setIncluded($included)
    {
        $this->included = $included;

        return $this;
    }

    public function changeTypeInAttributes($typeAlias)
    {
        if (!isset($this->attributes['type'])) {
            return $this;
        }

        $this->attributes[$typeAlias] = $this->attributes['type'];
        unset($this->attributes['type']);

        return $this;
    }
}

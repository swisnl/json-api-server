<?php

namespace Tests\TestClasses;

use Swis\JsonApi\Server\Repositories\BaseApiRepository;

class TestRepositoryWithRelationships extends BaseApiRepository
{
    public function getModelName(): string
    {
        return TestModelWithRelationships::class;
    }

    public function getQuery()
    {
        return $this->query;
    }
}

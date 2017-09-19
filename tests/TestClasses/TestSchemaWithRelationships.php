<?php

namespace Tests\TestClasses;

use Swis\LaravelApi\JsonSchemas\BaseApiSchema;

class TestSchemaWithRelationships extends BaseApiSchema
{
    protected $resourceType = 'test';

    public function getRepositoryClassName()
    {
        return TestRepositoryWithRelationships::class;
    }
}

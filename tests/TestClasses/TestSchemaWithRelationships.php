<?php

namespace Tests\TestClasses;

use Swis\LaravelApi\JsonSchemas\BaseApiSchema;

class TestSchemaWithRelationships extends BaseApiSchema
{

    public function getRepositoryClassName()
    {
        return TestRepositoryWithRelationships::class;
    }
}
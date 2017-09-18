<?php

namespace Sample\Schemas;

use Sample\Repositories\SampleRepository;
use Swis\LaravelApi\JsonSchemas\BaseApiSchema;

class SampleSchema extends BaseApiSchema
{
    protected $resourceType = 'samples';

    public function getRepositoryClassName()
    {
        return SampleRepository::class;
    }
}

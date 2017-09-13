<?php

namespace Tests\TestClasses;


use Swis\LaravelApi\Repositories\BaseApiRepository;

class TestRepositoryWithRelationships extends BaseApiRepository
{
    public function getModelName(): string
    {
        return TestModelWithRelationships::class;
    }
}
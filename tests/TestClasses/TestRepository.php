<?php

namespace Tests\TestClasses;

use Swis\LaravelApi\Repositories\BaseApiRepository;

class TestRepository extends BaseApiRepository
{
    public function getModelName(): string
    {
        return TestModel::class;
    }
}

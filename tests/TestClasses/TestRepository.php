<?php

namespace Tests\TestClasses;

use Swis\LaravelApi\Repositories\BaseApiRepository;

class TestRepository extends BaseApiRepository
{
    protected $modelName = TestModel::class;

    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function replaceModel($model)
    {
        $this->modelName = $model;
    }
}

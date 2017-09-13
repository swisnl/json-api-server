<?php

namespace Tests\TestClasses;

use Swis\LaravelApi\Repositories\BaseApiRepository;

class TestRepository extends BaseApiRepository
{
    public function getModelName(): string
    {
        return TestModel::class;
    }

    public function replaceModel($model)
    {
//        $this->model = $model;

        /*$mockModel = new Mock(Model::class);
        $testRepository->replaceModel($mockModel);
        $mockModel->expect('all')->once()->with('perPage', 1)->andReturn([]);

        $returnValue = $testRepository->all();

        $this->assertEquals($returnValue, []);*/
//
//        Mail::mock();
//        Mail::isCalledWith()

        // google: laravel test call to artisan
    }
}

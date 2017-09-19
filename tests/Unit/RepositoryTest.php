<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Tests\TestClasses\TestRepository;
use Tests\TestClasses\TestRepositoryWithRelationships;

class RepositoryTest extends TestCase
{
    /** @test */
    public function model_has_relationships()
    {
        $repository = new TestRepositoryWithRelationships();
        $this->assertEquals($repository->getModelRelationships(), ['0' => 'testModels']);
    }

    /** @test */
    public function make_model_not_instance_of_model()
    {
        $repository = new TestRepository();
        $repository->replaceModel(TestRepository::class);
        $this->expectException(ModelNotFoundException::class);
        $repository->makeModel();
    }
}

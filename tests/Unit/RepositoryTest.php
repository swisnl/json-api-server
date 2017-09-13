<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\TestClasses\TestRepositoryWithRelationships;

class RepositoryTest extends TestCase
{
    /** @test */
    public function model_has_relationships()
    {
        $repository = new TestRepositoryWithRelationships();
        $this->assertEquals($repository->getModelRelationships(), ['0' => 'testModels']);
    }
}

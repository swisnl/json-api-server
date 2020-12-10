<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Swis\JsonApi\Server\Exceptions\NotFoundException;
use Swis\JsonApi\Server\Paginators\EmptyPaginator;
use Tests\TestCase;
use Tests\TestClasses\TestModel;
use Tests\TestClasses\TestRepository;
use Tests\TestClasses\TestRepositoryWithRelationships;

class RepositoryTest extends TestCase
{
    /** @var TestRepositoryWithRelationships */
    private $testRepositoryWithRelationships;
    /** @var TestModel */
    private $testModel;
    /** @var TestRepository */
    private $testRepository;

    /**
     * Bootstrap the database
     * Setup a base repository which most test cases use.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->testModel = new TestModel(['title' => 'test', 'body' => 'test']);
        $this->testModel->save();
        $this->testRepository = new TestRepository();
        $this->testRepositoryWithRelationships = new TestRepositoryWithRelationships();
    }

    /** @test */
    public function modelHasRelationships()
    {
        $this->assertEquals(['0' => 'testModels'], $this->testRepositoryWithRelationships->getModelRelationships());
    }

    /** @test */
    public function makeModelNotInstanceOfModel()
    {
        $this->testRepository->replaceModel(TestRepository::class);
        $this->expectException(ModelNotFoundException::class);
        $this->testRepository->makeModel();
    }

    /** @test */
    public function setIdsWithParameters()
    {
        $this->testRepository->paginate($parameters = ['ids' => '1,2,3']);
        $this->assertEquals([1, 2, 3], $this->testRepository->getQuery()->wheres[0]['values']);
    }

    /** @test */
    public function setIdsWithoutParameters()
    {
        $this->assertEquals(null, $this->testRepository->setIds());
    }

    /** @test */
    public function excludeIdsWithParameters()
    {
        $this->testRepository->paginate($parameters = ['exclude_ids' => '1,2,3']);
        $this->assertEquals([1, 2, 3], $this->testRepository->getQuery()->wheres[0]['values']);
    }

    /** @test */
    public function excludeIdsWithoutParameters()
    {
        $this->assertEquals(null, $this->testRepository->excludeIds());
    }

    /** @test */
    public function orderByAscWithParameters()
    {
        $this->testRepository->paginate($parameters = ['order_by_asc' => true]);
        $this->assertEquals('asc', $this->testRepository->getQuery()->orders[0]['direction']);
    }

    /** @test */
    public function orderByAscWithoutParameters()
    {
        $this->assertEquals(null, $this->testRepository->orderByAsc());
    }

    /** @test */
    public function orderByDescWithParameters()
    {
        $this->testRepository->paginate($parameters = ['order_by_desc' => true]);
        $this->assertEquals('desc', $this->testRepository->getQuery()->orders[0]['direction']);
    }

    /** @test */
    public function orderByDescWithoutParameters()
    {
        $this->assertEquals(null, $this->testRepository->orderByDesc());
    }

    /** @test */
    public function paginateWithPage()
    {
        $this->testRepository->paginate($parameters = ['page' => 2]);
        $this->assertEquals(15, $this->testRepository->getQuery()->offset);
    }

    /** @test */
    public function paginateWithPerPage()
    {
        $this->testRepository->paginate($parameters = ['per_page' => 2]);
        $this->assertEquals(2, $this->testRepository->getQuery()->limit);
    }

    /** @test */
    public function paginateWithAllAttribute()
    {
        $this->assertEquals(1, $this->testRepository->paginate($parameters = ['all' => true])->total());
    }

    /** @test */
    public function paginateWithAllAttributeEmptyCollection()
    {
        $result = $this->testRepositoryWithRelationships->paginate(['all' => true]);
        $this->assertInstanceOf(EmptyPaginator::class, $result);
    }

    /** @test */
    public function setColumns()
    {
        $this->testRepository->paginate($parameters = ['fields' => 'title']);
        $this->assertContains('title', $this->testRepository->getColumns());
    }

    /** @test */
    public function findModelByIdWithoutQuery()
    {
        $model = $this->testRepository->findById(1);
        $this->assertEquals($model->title, $this->testModel->title);
    }

    /** @test */
    public function findModelByIdWithQuery()
    {
        $model = $this->testRepository->setParameters(['order_by_asc' => true])->findById(1);
        $this->assertEquals($model->title, $this->testModel->title);
    }

    /** @test */
    public function eagerLoadRelationShips()
    {
        $this->testRepositoryWithRelationships->paginate(['include' => 'testModels']);
        $this->assertCount(1, $this->testRepositoryWithRelationships->getQuery()->getEagerLoads());
    }

    /** @test */
    public function createModel()
    {
        $this->testRepository->create(['title' => 'test', 'body' => 'test']);
        $this->assertDatabaseHas('test_models', [
            'id' => 2,
            'title' => 'test',
            'body' => 'test',
        ]);
    }

    /** @test */
    public function createModelWithNullValue()
    {
        $this->testRepository->create(['title' => 'test', 'body' => null]);
        $this->assertDatabaseHas('test_models', [
            'id' => 2,
            'title' => 'test',
            'body' => '',
        ]);
    }

    /** @test
     * @throws \Swis\JsonApi\Server\Exceptions\NotFoundException
     */
    public function updateModel()
    {
        $this->testRepository->update(['title' => 'test2', 'body' => 'test'], 1);
        $this->assertDatabaseHas('test_models', [
            'title' => 'test2',
            'body' => 'test',
        ]);
    }

    /** @test
     * @throws \Swis\JsonApi\Server\Exceptions\NotFoundException
     */
    public function updateModelNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->testRepository->update(['title' => 'test2', 'body' => 'test'], 2);
    }

    /** @test */
    public function destroyModel()
    {
        $this->testRepository->destroy(1);
        $this->assertDatabaseMissing('test_models', [
            'title' => 'test',
            'body' => 'test',
        ]);
    }
}

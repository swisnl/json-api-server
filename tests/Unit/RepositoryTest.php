<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Swis\JsonApi\Server\Exceptions\NotFoundException;
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
    protected function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->testRepository = new TestRepository();
        $this->testRepositoryWithRelationships = new TestRepositoryWithRelationships();
    }

    /**
     * Setup the database for this test file.
     *
     * @param $app
     */
    private function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('body');
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_model_with_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('body');
            $table->integer('test_model_id')->unsigned();
            $table->foreign('test_model_id')->references('id')->on('test_models');
        });

        $this->testModel = new TestModel(['title' => 'test', 'body' => 'test']);
        $this->testModel->save();
    }

    /** @test */
    public function model_has_relationships()
    {
        $this->assertEquals(['0' => 'testModels'], $this->testRepositoryWithRelationships->getModelRelationships());
    }

    /** @test */
    public function make_model_not_instance_of_model()
    {
        $this->testRepository->replaceModel(TestRepository::class);
        $this->expectException(ModelNotFoundException::class);
        $this->testRepository->makeModel();
    }

    /** @test */
    public function set_ids_with_parameters()
    {
        $this->testRepository->paginate($parameters = ['ids' => '1,2,3']);
        $this->assertEquals([1, 2, 3], $this->testRepository->getQuery()->wheres[0]['values']);
    }

    /** @test */
    public function set_ids_without_parameters()
    {
        $this->assertEquals(null, $this->testRepository->setIds());
    }

    /** @test */
    public function exclude_ids_with_parameters()
    {
        $this->testRepository->paginate($parameters = ['exclude_ids' => '1,2,3']);
        $this->assertEquals([1, 2, 3], $this->testRepository->getQuery()->wheres[0]['values']);
    }

    /** @test */
    public function exclude_ids_without_parameters()
    {
        $this->assertEquals(null, $this->testRepository->excludeIds());
    }

    /** @test */
    public function order_by_asc_with_parameters()
    {
        $this->testRepository->paginate($parameters = ['order_by_asc' => true]);
        $this->assertEquals('asc', $this->testRepository->getQuery()->orders[0]['direction']);
    }

    /** @test */
    public function order_by_asc_without_parameters()
    {
        $this->assertEquals(null, $this->testRepository->orderByAsc());
    }

    /** @test */
    public function order_by_desc_with_parameters()
    {
        $this->testRepository->paginate($parameters = ['order_by_desc' => true]);
        $this->assertEquals('desc', $this->testRepository->getQuery()->orders[0]['direction']);
    }

    /** @test */
    public function order_by_desc_without_parameters()
    {
        $this->assertEquals(null, $this->testRepository->orderByDesc());
    }

    /** @test */
    public function paginate_with_page()
    {
        $this->testRepository->paginate($parameters = ['page' => 2]);
        $this->assertEquals(15, $this->testRepository->getQuery()->offset);
    }

    /** @test */
    public function paginate_with_per_page()
    {
        $this->testRepository->paginate($parameters = ['per_page' => 2]);
        $this->assertEquals(2, $this->testRepository->getQuery()->limit);
    }

    /** @test */
    public function paginate_with_all_attribute()
    {
        $this->assertEquals(1, $this->testRepository->paginate($parameters = ['all' => true])->total());
    }

    /** @test */
    public function set_columns()
    {
        $this->testRepository->paginate($parameters = ['fields' => 'title']);
        $this->assertContains('title', $this->testRepository->getColumns());
    }

    /** @test */
    public function find_model_by_id_without_query()
    {
        $model = $this->testRepository->findById(1);
        $this->assertEquals($model->title, $this->testModel->title);
    }

    /** @test */
    public function find_model_by_id_with_query()
    {
        $model = $this->testRepository->setParameters(['order_by_asc' => true])->findById(1);
        $this->assertEquals($model->title, $this->testModel->title);
    }

    /** @test */
    public function eager_load_relation_ships()
    {
        $this->testRepositoryWithRelationships->paginate(['include' => 'testModels']);
        $this->assertCount(1, $this->testRepositoryWithRelationships->getQuery()->getEagerLoads());
    }

    /** @test */
    public function create_model()
    {
        $this->testRepository->create(['title' => 'test', 'body' => 'test']);
        $this->assertDatabaseHas('test_models', [
            'id' => 2,
            'title' => 'test',
            'body' => 'test',
        ]);
    }

    /** @test */
    public function create_model_with_null_value()
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
    public function update_model()
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
    public function update_model_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->testRepository->update(['title' => 'test2', 'body' => 'test'], 2);
    }

    /** @test */
    public function destroy_model()
    {
        $this->testRepository->destroy(1);
        $this->assertDatabaseMissing('test_models', [
            'title' => 'test',
            'body' => 'test',
        ]);
    }
}

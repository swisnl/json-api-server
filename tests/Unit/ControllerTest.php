<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 19-2-2018
 * Time: 11:55.
 */

namespace Tests\Unit;

use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\TestClasses\TestController;
use Tests\TestClasses\TestModel;

class ControllerTest extends TestCase
{
    /** @var TestController $testController */
    protected $testController;
    /** @var Response */
    protected $response;
    /** @var TestModel */
    protected $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->testModel = new TestModel(['title' => 'test', 'body' => 'test']);
        $this->testModel->save();
        $this->testController = $this->app->make(TestController::class);
    }

    /**
     * A basic test example.
     *
     * @throws \Swis\JsonApi\Server\Exceptions\ForbiddenException
     */
    public function test_index()
    {
        $this->response = $this->testController->index();
        $collection = $this->response->getContent();
        $this->assertContains('data', $collection);
    }

    /**
     * @throws \Swis\JsonApi\Server\Exceptions\ForbiddenException
     */
    public function test_show()
    {
        $this->response = $this->testController->show(1);
        $collection = json_decode($this->response->getContent());
        $this->assertEquals($collection->data->attributes->title, 'test');
    }

    /**
     * @throws \Swis\JsonApi\Server\Exceptions\ForbiddenException
     */
    public function test_delete()
    {
        $this->response = $this->testController->delete(1);
        $this->assertDatabaseMissing('test_models', [
            'title' => 'test',
            'body' => 'test',
        ]);
    }
}

<?php

namespace Tests\Unit;

use Illuminate\Pagination\LengthAwarePaginator;
use Swis\LaravelApi\Models\Responses\RespondHttpForbidden;
use Swis\LaravelApi\Models\Responses\RespondHttpNotFound;
use Swis\LaravelApi\Models\Responses\RespondHttpUnauthorized;
use Swis\LaravelApi\Services\ResponseService;
use Swis\LaravelApi\Traits\HandleResponses;
use Tests\TestCase;
use Tests\TestClasses\TestModel;

class ResponseServiceTest extends TestCase
{
    use HandleResponses;

    protected $testModel;
    /**
     * @var ResponseService
     */
    private $responseService;

    public function setUp()
    {
        parent::setUp();
        $this->responseService = new ResponseService();
        $this->testModel = new TestModel();
    }

    /** @test */
    public function it_creates_an_OK_response()
    {
        $this->testModel->body = 'OK';
        $response = $this->respondWithOK($this->testModel);
        $responseBody = json_decode($response->getContent())->data;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $responseBody->attributes->body);
    }

    /** @test */
    public function it_creates_a_partial_content_response()
    {
        $this->testModel->body = 'PARTIAL';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);
        $response = $this->respondWithPartialContent($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $responseBody);
    }

    /** @test */
    public function it_creates_a_created_response()
    {
        $this->testModel->body = 'CREATED';
        $response = $this->respondWithCreated($this->testModel);
        $responseBody = json_decode($response->getContent())->data->attributes->body;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('CREATED', $responseBody);
    }

    /** @test */
    public function it_creates_a_no_content_response()
    {
        $response = $this->respondWithNoContent();

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_creates_a_collection_response_with_OK()
    {
        $this->testModel->body = 'OK';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);
        $response = $this->respondWithCollectionOK($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $responseBody);
    }

    /** @test */
    public function it_creates_a_collection_response_with_PARTIAL()
    {
        $this->testModel->body = 'PARTIAL';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);

        $response = $this->respondWithCollection($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $responseBody);
    }

    /** @test */
    public function it_creates_a_forbidden_response()
    {
        $message = 'FORBIDDEN';
        $response = $this->responseService->response(RespondHttpForbidden::class, $message);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('FORBIDDEN', json_decode($response->getContent())->errors[0]->detail);
    }

    /** @test */
    public function it_creates_a_not_found_response()
    {
        $message = 'NOT FOUND';
        $response = $this->responseService->response(RespondHttpNotFound::class, $message);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('NOT FOUND', json_decode($response->getContent())->errors[0]->detail);
    }

    /** @test */
    public function it_creates_a_unauthorized_response()
    {
        $message = 'UNAUTHORIZED';
        $response = $this->responseService->response(RespondHttpUnauthorized::class, $message);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('UNAUTHORIZED', json_decode($response->getContent())->errors[0]->detail);
    }
}

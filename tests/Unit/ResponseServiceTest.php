<?php

namespace Tests\Unit;

use Illuminate\Pagination\LengthAwarePaginator;
use Swis\JsonApi\Server\Exceptions\NotFoundException;
use Swis\JsonApi\Server\Models\Responses\RespondHttpForbidden;
use Swis\JsonApi\Server\Models\Responses\RespondHttpNotFound;
use Swis\JsonApi\Server\Models\Responses\RespondHttpUnauthorized;
use Swis\JsonApi\Server\Services\ResponseService;
use Swis\JsonApi\Server\Traits\HandleResponses;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->responseService = new ResponseService();
        $this->testModel = new TestModel();
    }

    /** @test */
    public function itCreatesAnOKResponse()
    {
        $this->testModel->body = 'OK';
        $response = $this->respondWithOK($this->testModel);
        $responseBody = json_decode($response->getContent())->data;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $responseBody->attributes->body);
    }

    /** @test */
    public function itCreatesAPartialContentResponse()
    {
        $this->testModel->body = 'PARTIAL';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);
        $response = $this->respondWithPartialContent($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $responseBody);
    }

    /** @test */
    public function itCreatesACreatedResponse()
    {
        $this->testModel->body = 'CREATED';
        $response = $this->respondWithCreated($this->testModel);
        $responseBody = json_decode($response->getContent())->data->attributes->body;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('CREATED', $responseBody);
    }

    /** @test */
    public function itCreatesANoContentResponse()
    {
        $response = $this->respondWithNoContent();

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function itCreatesACollectionResponseWithOK()
    {
        $this->testModel->body = 'OK';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);
        $response = $this->respondWithCollectionOK($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $responseBody);
    }

    /** @test */
    public function itCreatesACollectionResponseWithPARTIAL()
    {
        $this->testModel->body = 'PARTIAL';
        $paginator = new LengthAwarePaginator([$this->testModel], 1, 1);

        $response = $this->respondWithCollection($paginator);
        $responseBody = json_decode($response->getContent())->data[0]->attributes->body;

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $responseBody);
    }

    /** @test */
    public function itCreatesANotFoundResponseInResource()
    {
        $this->expectException(NotFoundException::class);
        $this->respondWithResource($this->testModel, null);
    }

    /** @test */
    public function itCreatesAForbiddenResponse()
    {
        $message = 'FORBIDDEN';
        $response = $this->responseService->response(RespondHttpForbidden::class, $message);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('FORBIDDEN', json_decode($response->getContent())->errors[0]->detail);
    }

    /** @test */
    public function itCreatesANotFoundResponse()
    {
        $message = 'NOT FOUND';
        $response = $this->responseService->response(RespondHttpNotFound::class, $message);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('NOT FOUND', json_decode($response->getContent())->errors[0]->detail);
    }

    /** @test */
    public function itCreatesAUnauthorizedResponse()
    {
        $message = 'UNAUTHORIZED';
        $response = $this->responseService->response(RespondHttpUnauthorized::class, $message);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('UNAUTHORIZED', json_decode($response->getContent())->errors[0]->detail);
    }
}

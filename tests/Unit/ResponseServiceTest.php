<?php

namespace Tests\Unit;

use Swis\LaravelApi\Models\Responses\RespondHttpForbidden;
use Swis\LaravelApi\Models\Responses\RespondHttpNotFound;
use Swis\LaravelApi\Models\Responses\RespondHttpUnauthorized;
use Swis\LaravelApi\Services\ResponseService;
use Swis\LaravelApi\Traits\HandleResponses;
use Tests\TestCase;

class ResponseServiceTest extends TestCase
{
    use HandleResponses;
    /**
     * @var ResponseService
     */
    private $responseService;

    public function setUp()
    {
        parent::setUp();
        $this->responseService = new ResponseService();
    }

    /** @test */
    public function it_creates_an_OK_response()
    {
        $message = 'OK';
        $response = $this->respondWithOK($message);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_creates_a_partial_content_response()
    {
        $message = 'PARTIAL';
        $response = $this->respondWithPartialContent($message);

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $response->getContent());
    }

    /** @test */
    public function it_creates_a_created_response()
    {
        $message = 'CREATED';
        $response = $this->respondWithCreated($message);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('CREATED', $response->getContent());
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
        $message = 'OK';
        $response = $this->respondWithCollection($message);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_creates_a_collection_response_with_PARTIAL()
    {
        $message = ['meta' => 'test'];
        $response = $this->respondWithCollection(json_encode($message));

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('{"meta":"test"}', $response->getContent());
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

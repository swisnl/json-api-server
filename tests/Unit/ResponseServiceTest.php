<?php

namespace Swis\test\Unit;

use Orchestra\Testbench\TestCase;
use Swis\LaravelApi\Models\Responses\RespondHttpForbidden;
use Swis\LaravelApi\Models\Responses\RespondHttpNotFound;
use Swis\LaravelApi\Models\Responses\RespondHttpOk;
use Swis\LaravelApi\Models\Responses\RespondHttpPartialContent;
use Swis\LaravelApi\Models\Responses\RespondHttpUnauthorized;
use Swis\LaravelApi\Services\ResponseService;

class ResponseServiceTest extends TestCase
{
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
        $response = $this->responseService->response(RespondHttpOk::class, $message);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_creates_a_partial_content_response()
    {
        $message = 'PARTIAL';
        $response = $this->responseService->response(RespondHttpPartialContent::class, $message);

        $this->assertEquals(206, $response->getStatusCode());
        $this->assertEquals('PARTIAL', $response->getContent());
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

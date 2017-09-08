<?php

namespace Swis\LaravelApi\Traits;

use Swis\LaravelApi\Models\Responses\RespondHttpCreated;
use Swis\LaravelApi\Models\Responses\RespondHttpNoContent;
use Swis\LaravelApi\Models\Responses\RespondHttpOk;
use Swis\LaravelApi\Models\Responses\RespondHttpPartialContent;
use Swis\LaravelApi\Services\ResponseService;

trait HandleResponses
{
    protected function respond(string $strResponseModel, string $content)
    {
        $responseService = new ResponseService();

        return $responseService->response($strResponseModel, $content);
    }

    public function respondWithOK(string $content)
    {
        return $this->respond(RespondHttpOk::class, $content);
    }

    public function respondWithPartialContent($content)
    {
        return $this->respond(RespondHttpPartialContent::class, $content);
    }

    public function respondWithCreated($content)
    {
        return $this->respond(RespondHttpCreated::class, $content);
    }

    public function respondWithNoContent()
    {
        return $this->respond(RespondHttpNoContent::class, '');
    }

    public function respondWithCollection($content)
    {
        if (!array_has(json_decode($content), 'meta')) {//TODO: later json encoder hier doen?
            return $this->respondWithOK($content);
        }

        return $this->respondWithPartialContent($content);
    }
}

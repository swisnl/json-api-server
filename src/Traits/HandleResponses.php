<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Swis\LaravelApi\Models\Responses\RespondHttpCreated;
use Swis\LaravelApi\Models\Responses\RespondHttpNoContent;
use Swis\LaravelApi\Models\Responses\RespondHttpOk;
use Swis\LaravelApi\Models\Responses\RespondHttpPartialContent;
use Swis\LaravelApi\Services\ResponseService;

trait HandleResponses
{
    protected function respond($respondModel, $content)
    {
        $service = new ResponseService();

        return $service->response($respondModel, $content);
    }

    protected function respondWithResource($respondModel, $content)
    {
        $service = new ResponseService();

        return $service->responseWithResource($respondModel, $content);
    }

    protected function respondWithResourceCollection($respondModel, $content)
    {
        $service = new ResponseService();

        return $service->respondWithResourceCollection($respondModel, $content);
    }

    public function respondWithOK($content)
    {
        return $this->respondWithResource(RespondHttpOk::class, $content);
    }

    public function respondWithPartialContent($content)
    {
        return $this->respondWithResourceCollection(RespondHttpPartialContent::class, $content);
    }

    protected function respondWithCollectionOK($content)
    {
        return $this->respondWithResourceCollection(RespondHttpOk::class, $content);
    }

    public function respondWithCreated($content)
    {
        return $this->respondWithResource(RespondHttpCreated::class, $content);
    }

    public function respondWithNoContent()
    {
        return $this->respond(RespondHttpNoContent::class, '');
    }

    public function respondWithCollection($content)
    {
        if ($content instanceof Paginator) {
            return $this->respondWithPartialContent($content);
        }

        return $this->respondWithCollectionOK($content);
    }
}

<?php

namespace Swis\LaravelApi\Traits;

use Swis\LaravelApi\JsonEncoders\JsonEncoder;
use Swis\LaravelApi\Models\Responses\RespondHttpCreated;
use Swis\LaravelApi\Models\Responses\RespondHttpNoContent;
use Swis\LaravelApi\Models\Responses\RespondHttpOk;
use Swis\LaravelApi\Models\Responses\RespondHttpPartialContent;
use Swis\LaravelApi\Services\ResponseService;

trait HandleResponses
{

    protected $repository;
    protected $jsonEncoder;

    protected function respond(string $strResponseModel, string $content)
    {
        $responseService = new ResponseService();

        return $responseService->response($strResponseModel, $content);
    }

    public function respondWithOK($content)
    {
        $jsonObject = $this->encodeObjectToJson($content);

        return $this->respond(RespondHttpOk::class, $jsonObject);
    }

    public function respondWithPartialContent($content)
    {
        $jsonObject = $this->encodeObjectToJson($content);

        return $this->respond(RespondHttpPartialContent::class, $jsonObject);
    }

    public function respondWithCreated($content)
    {
        $jsonObject = $this->encodeObjectToJson($content);

        return $this->respond(RespondHttpCreated::class, $jsonObject);
    }

    public function respondWithNoContent()
    {
        return $this->respond(RespondHttpNoContent::class, '');
    }

    public function respondWithCollection($content)
    {
        if (is_array($content)) {
            return $this->respondWithPartialContent($content);
        }
        return $this->respondWithOK($content);
    }

    public function setResponseRepository($repository)
    {
        $this->repository = $repository;
        $this->setJsonEncoder();
        return $this;
    }

    public function setJsonEncoder()
    {
        $this->jsonEncoder = new JsonEncoder();
        $this->jsonEncoder->setRepository($this->repository);
        return $this;
    }

    public function encodeObjectToJson($object)
    {
        return $this->jsonEncoder->encodeToJson($object);
    }
}

<?php

namespace Swis\LaravelApi\Services;

use Swis\LaravelApi\Http\Resources\BaseApiCollectionResource;
use Swis\LaravelApi\Http\Resources\BaseApiResource;
use Swis\LaravelApi\Models\Responses\RespondError;

class ResponseService
{
    public function __construct()
    {
    }

    public function response($strResponseModel, $content = null)
    {
        $responseModel = new $strResponseModel();

        return $this->createResponse($responseModel, $content);
    }

    protected function createResponse($responseModel, $content)
    {
        if ($responseModel instanceof RespondError) { //TODO: tijdelijk snel hier geformat
            $error = ['errors' => [
                'status_code' => $responseModel->getStatusCode(),
                'message' => $responseModel->getMessage(),
                'detail' => $content,
            ]];

            return response($error, $responseModel->getStatusCode());
        }

        return response($content, $responseModel->getStatusCode());
    }

    public function respondWithResourceCollection($strResponseModel, $content)
    {
        $responseModel = new $strResponseModel();

        return (new BaseApiCollectionResource($content))
            ->response()
            ->setStatusCode($responseModel->getStatusCode());
    }

    public function responseWithResource($strResponseModel, $content)
    {
        $responseModel = new $strResponseModel();

        return (new BaseApiResource($content))
            ->response()
            ->setStatusCode($responseModel->getStatusCode());
    }
}

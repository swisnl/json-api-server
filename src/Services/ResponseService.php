<?php

namespace Swis\JsonApi\Server\Services;

use Swis\JsonApi\Server\Http\Resources\BaseApiCollectionResource;
use Swis\JsonApi\Server\Http\Resources\BaseApiResource;
use Swis\JsonApi\Server\Models\Responses\RespondError;

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
            $errors = [];

            $errors['errors'] = [
                0 => [
                    'status' => (string) $responseModel->getStatusCode(),
                    'title' => (string) $responseModel->getMessage(),
                    'detail' => (string) $content,
                ],
            ];

            return response($errors, $responseModel->getStatusCode());
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

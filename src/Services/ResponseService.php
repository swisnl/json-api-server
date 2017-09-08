<?php

namespace Swis\LaravelApi\Services;

use Swis\LaravelApi\JsonEncoders\ErrorsJsonEncoder;
use Swis\LaravelApi\Models\Responses\RespondError;

class ResponseService
{
    protected $errorEncoder;

    public function __construct()
    {
        $this->errorEncoder = new ErrorsJsonEncoder();
    }

    public function response($strResponseModel, $content = null)
    {
        $responseModel = new $strResponseModel();

        return $this->createResponse($responseModel, $content);
    }

    protected function createResponse($responseModel, $content)
    {
        if ($responseModel instanceof RespondError) {
            $error = $this->errorEncoder->encodeError($responseModel, $content);

            return response($error, $responseModel->getStatusCode());
        }

        return response($content, $responseModel->getStatusCode());
    }
}

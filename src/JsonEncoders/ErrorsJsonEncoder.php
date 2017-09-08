<?php

namespace Swis\LaravelApi\JsonEncoders;

use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;

class ErrorsJsonEncoder
{
    /**
     * Example to show how an error is constructed in JSON API format.
     * An error also accepts an id and about link but that will not be supported by this API.
     *
     * @param $responseModel
     * @param $errorDetail
     *
     * @return string
     *
     * @internal param $errorCode
     * @internal param $errorTitle
     * @internal param $errorStatus
     */
    public function encodeError($responseModel, $errorDetail)
    {
        $error = new Error(
            null,
            null,
            null,
            $responseModel->getStatusCode(),
            $responseModel->getMessage(),
            $errorDetail
        );

        return Encoder::instance([], new EncoderOptions(JSON_PRETTY_PRINT))->encodeError($error);
    }
}

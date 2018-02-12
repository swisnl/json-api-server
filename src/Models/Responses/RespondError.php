<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

abstract class RespondError
{
    protected $statusCode = HttpCodes::HTTP_BAD_REQUEST;
    protected $message;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getMessage()
    {
        return $this->message;
    }
}

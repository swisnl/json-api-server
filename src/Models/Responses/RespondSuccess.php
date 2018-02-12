<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

abstract class RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}

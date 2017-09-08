<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

abstract class RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}

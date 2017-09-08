<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpOk extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_OK;
}

<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpNoContent extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_NO_CONTENT;
}

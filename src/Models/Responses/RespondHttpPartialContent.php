<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpPartialContent extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_PARTIAL_CONTENT;
}

<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpUnauthorized extends RespondError
{
    protected $statusCode = HttpCodes::HTTP_UNAUTHORIZED;
    protected $message = 'Unauthorized';
}

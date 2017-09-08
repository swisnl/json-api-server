<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpNotFound extends RespondError
{
    protected $statusCode = HttpCodes::HTTP_NOT_FOUND;
    protected $message = 'Not Found';
}

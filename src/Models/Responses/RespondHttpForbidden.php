<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpForbidden extends RespondError
{
    protected $statusCode = HttpCodes::HTTP_FORBIDDEN;
    protected $message = 'Forbidden';
}

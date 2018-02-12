<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpUnauthorized extends RespondError
{
    protected $statusCode = HttpCodes::HTTP_UNAUTHORIZED;
    protected $message = 'Unauthorized';
}

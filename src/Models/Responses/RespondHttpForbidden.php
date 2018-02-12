<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpForbidden extends RespondError
{
    protected $statusCode = HttpCodes::HTTP_FORBIDDEN;
    protected $message = 'Forbidden';
}

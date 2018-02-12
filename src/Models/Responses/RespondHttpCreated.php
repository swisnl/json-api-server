<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpCreated extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_CREATED;
}

<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpNoContent extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_NO_CONTENT;
}

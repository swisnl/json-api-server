<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpPartialContent extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_PARTIAL_CONTENT;
}

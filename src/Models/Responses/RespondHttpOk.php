<?php

namespace Swis\JsonApi\Server\Models\Responses;

use Swis\JsonApi\Server\Constants\HttpCodes;

class RespondHttpOk extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_OK;
}

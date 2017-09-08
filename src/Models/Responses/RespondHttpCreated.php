<?php

namespace Swis\LaravelApi\Models\Responses;

use Swis\LaravelApi\Constants\HttpCodes;

class RespondHttpCreated extends RespondSuccess
{
    protected $statusCode = HttpCodes::HTTP_CREATED;
}

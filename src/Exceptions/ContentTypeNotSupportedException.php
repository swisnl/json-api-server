<?php

namespace Swis\LaravelApi\Exceptions;

use Swis\LaravelApi\Constants\HttpCodes;

class ContentTypeNotSupportedException extends JsonException
{
    protected $code = HttpCodes::HTTP_UNSUPPORTED_MEDIA_TYPE;
}

<?php

namespace Swis\JsonApi\Server\Exceptions;

use Swis\JsonApi\Server\Constants\HttpCodes;

class ContentTypeNotSupportedException extends JsonException
{
    protected $code = HttpCodes::HTTP_UNSUPPORTED_MEDIA_TYPE;
}

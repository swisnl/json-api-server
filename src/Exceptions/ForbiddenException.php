<?php

namespace Swis\LaravelApi\Exceptions;

use Swis\LaravelApi\Constants\HttpCodes;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(HttpCodes::HTTP_FORBIDDEN, $message, $previous, [], $code);
    }
}

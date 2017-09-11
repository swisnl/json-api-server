<?php

namespace Swis\LaravelApi\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Swis\LaravelApi\Constants\HttpCodes;

class SchemaNotFoundException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(HttpCodes::HTTP_NOT_FOUND, $message, $previous, [], $code);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 7-2-2018
 * Time: 16:45.
 */

namespace Swis\JsonApi\Server\Exceptions;

use Exception;

class JsonExceptionRenderer
{
    public function formatErrors(Exception $exception): array
    {
        return ['message' => $exception->getMessage(),
            'status' => $exception->getCode(), ];
    }
}

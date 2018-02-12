<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 7-2-2018
 * Time: 17:04.
 */

namespace Swis\JsonApi\Server\Exceptions;

use Exception;

class AuthorizationExceptionRenderer
{
    /**
     * @param Exception $exception
     *
     * @return array
     */
    public function formatErrors(Exception $exception): array
    {
        return ['message' => $exception->getMessage(), 'status' => '401'];
    }
}

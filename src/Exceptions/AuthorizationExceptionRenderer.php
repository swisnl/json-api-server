<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 7-2-2018
 * Time: 17:04.
 */

namespace Swis\JsonApi\Server\Exceptions;

use Exception;
use Swis\JsonApi\Server\Constants\HttpCodes;

class AuthorizationExceptionRenderer
{
    public $status = HttpCodes::HTTP_UNAUTHORIZED;
    /**
     * @param Exception $exception
     *
     * @return array
     */
    public function formatErrors(Exception $exception): array
    {
        return ['message' => $exception->getMessage(), 'status' => $this->status];
    }
}

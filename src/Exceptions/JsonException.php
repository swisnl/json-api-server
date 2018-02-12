<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 7-2-2018
 * Time: 13:36.
 */

namespace Swis\JsonApi\Server\Exceptions;

use Exception;
use Swis\JsonApi\Server\Constants\HttpCodes;
use Swis\JsonApi\Server\Traits\HandleResponses;

class JsonException extends Exception
{
    use HandleResponses;
    protected $code = HttpCodes::HTTP_BAD_REQUEST;

    public function render() {
        return $this->respondWithBadRequest($this->message);
    }
}

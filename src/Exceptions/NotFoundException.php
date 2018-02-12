<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 12-2-2018
 * Time: 13:09
 */

namespace Swis\JsonApi\Server\Exceptions;

class NotFoundException extends JsonException
{
    public function render()
    {
        return $this->respondWithNotFound($this->message);
    }

}
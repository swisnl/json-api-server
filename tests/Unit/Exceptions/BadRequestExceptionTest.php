<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 14-2-2018
 * Time: 15:04.
 */

namespace Tests\Unit\Exceptions;

use Swis\JsonApi\Server\Exceptions\BadRequestException;
use Tests\TestCase;

class BadRequestExceptionTest extends TestCase
{
    public function testRender()
    {
        $badRequestException = new BadRequestException('BAD REQUEST');
        $response = $badRequestException->render();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('BAD REQUEST', $response->getContent());
    }
}

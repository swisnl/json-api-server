<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 14-2-2018
 * Time: 15:00
 */

namespace Tests\Unit\Exceptions;

use Swis\JsonApi\Server\Exceptions\ForbiddenException;
use Tests\TestCase;

class ForbiddenExceptionTest extends TestCase
{

    public function testRender()
    {
        $forbiddenException = new ForbiddenException('FORBIDDEN');
        $response = $forbiddenException->render();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertContains('FORBIDDEN', $response->getContent());
    }
}

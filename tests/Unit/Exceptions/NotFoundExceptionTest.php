<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 14-2-2018
 * Time: 14:54
 */

namespace Tests\Unit\Exceptions;


use Swis\JsonApi\Server\Exceptions\NotFoundException;
use Tests\TestCase;

class NotFoundExceptionTest extends TestCase
{

    public function testRender()
    {
        $notFoundException = new NotFoundException('NOT FOUND');
        $response = $notFoundException->render();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertContains('NOT FOUND', $response->getContent());
    }
}

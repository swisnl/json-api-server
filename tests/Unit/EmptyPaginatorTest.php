<?php

namespace Swis\JsonApi\Server\Paginators;

use Tests\TestCase;

class EmptyPaginatorTest extends TestCase
{
    /** @var EmptyPaginator */
    private $emptyPaginator;

    protected function setUp(): void
    {
        $this->emptyPaginator = new EmptyPaginator();
    }

    /** @test */
    public function testTotal()
    {
        $this->assertEquals(0, $this->emptyPaginator->total());
    }

    /** @test */
    public function testToArray()
    {
        $this->assertEquals([], $this->emptyPaginator->toArray()['data']);
        $this->assertEquals(0, $this->emptyPaginator->toArray()['total']);
    }
}

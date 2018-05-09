<?php

namespace Swis\JsonApi\Server\Paginators;

use Tests\TestCase;

class EmptyPaginatorTest extends TestCase
{
    /** @var EmptyPaginator */
    private $emptyPaginator;

    protected function setUp()
    {
        $this->emptyPaginator = new EmptyPaginator();
    }

    /** @test */
    public function test_total()
    {
        $this->assertEquals(0, $this->emptyPaginator->total());
    }

    /** @test */
    public function test_to_array()
    {
        $this->assertEquals([], $this->emptyPaginator->toArray()['data']);
        $this->assertEquals(0, $this->emptyPaginator->toArray()['total']);
    }
}

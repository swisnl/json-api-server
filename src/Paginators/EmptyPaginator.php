<?php

namespace Swis\JsonApi\Server\Paginators;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

class EmptyPaginator extends AbstractPaginator
{
    protected $total;

    public function __construct($items = [], $total = 0)
    {
        $this->items = $items instanceof Collection ? $items : Collection::make($items);
        $this->total = 0;
    }

    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'from' => $this->firstItem(),
            'path' => $this->path,
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }

    public function total()
    {
        return $this->total;
    }
}

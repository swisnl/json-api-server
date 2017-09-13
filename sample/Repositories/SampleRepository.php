<?php

namespace Sample\Repositories;

use Swis\LaravelApi\Repositories\BaseApiRepository;
use Swis\sample\Sample;

class SampleRepository extends BaseApiRepository
{
    public function getModelName(): string
    {
        return Sample::class;
    }
}

<?php

namespace Swis\sample\Repositories;

use Swis\sample\Sample;
use Swis\LaravelApi\Repositories\BaseApiRepository;

class SampleRepository extends BaseApiRepository
{
    public function getModelName(): string
    {
        return Sample::class;
    }
}
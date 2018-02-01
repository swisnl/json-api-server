<?php

namespace App\Repositories;

use App\Sample;
use Swis\LaravelApi\Repositories\BaseApiRepository;

class SampleRepository extends BaseApiRepository
{
    public function getModelName(): string
    {
        return Sample::class;
    }
}

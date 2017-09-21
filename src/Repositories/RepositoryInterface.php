<?php

namespace Swis\LaravelApi\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function getModelRelationships(): array;

    public function makeModel(): Model;

    public function getModelName();
}

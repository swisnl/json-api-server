<?php

namespace Swis\LaravelApi\Repositories;

interface RepositoryInterface
{
    public function getModelRelationships(): array;
}
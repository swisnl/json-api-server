<?php

namespace Swis\LaravelApi\Models;

interface ModelContract
{
    public function getRelationships(): array;

    public function getSchema(): string;

    public function getRepository(): string;

    public function getTranslatable();

    public function getRules($id = null): array;
}

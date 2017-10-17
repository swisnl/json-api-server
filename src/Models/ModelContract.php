<?php

namespace Swis\LaravelApi\Models;

interface ModelContract
{
    public function getTranslatable();

    public function getRules($id = null): array;
}

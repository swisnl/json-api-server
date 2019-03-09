<?php

namespace Tests;

class CommandTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['laravel_api.path.templates' => 'resources/templates/']);
        config(['laravel_api.path.controller' => 'tests/Data/Output/']);
    }
}

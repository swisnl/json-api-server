<?php

namespace Tests;

class CommandTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        config(['laravel_api.path.templates' => 'resources/templates/']);
        config(['laravel_api.path.controller' => 'tests/Data/Output/']);
    }
}

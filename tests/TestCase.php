<?php

namespace Tests;

use Swis\LaravelApi\Providers\LaravelApiServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [LaravelApiServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dir = substr(__DIR__, 0,  strpos(__DIR__, 'tests'));
        app()->setBasePath($dir);
    }
}
<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Swis\JsonApi\Server\Providers\LaravelApiServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        config(['laravel_api.path.routes' => 'tests/TestClasses/Routes']);

        return [LaravelApiServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dir = substr(__DIR__, 0, strpos(__DIR__, 'tests'));
        app()->setBasePath($dir);
    }

    /**
     * Setup the database for this test file.
     *
     * @param $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('body');
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_model_with_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('body');
            $table->integer('test_model_id')->unsigned();
            $table->foreign('test_model_id')->references('id')->on('test_models');
        });
    }
}

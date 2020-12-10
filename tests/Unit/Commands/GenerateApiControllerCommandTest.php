<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:27.
 */

namespace Swis\JsonApi\Server\Console\Commands;

use Tests\CommandTestCase;

class GenerateApiControllerCommandTest extends CommandTestCase
{
    /** @test */
    public function itGeneratesAnApiController()
    {
        $this->artisan('json-api-server:generate-controller', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        unlink('tests/Data/Output/'.'ExampleController.php');
    }

    /** @test */
    public function itGeneratesAnApiControllerWithoutOverride()
    {
        $this->artisan('json-api-server:generate-controller', ['name' => 'Example']);
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        unlink('tests/Data/Output/'.'ExampleController.php');
    }
}

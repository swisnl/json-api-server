<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:27.
 */

namespace Swis\LaravelApi\Console\Commands;

use Tests\CommandTestCase;

class GenerateApiControllerCommandTest extends CommandTestCase
{
    /** @test */
    public function it_generates_an_api_controller()
    {
        $this->artisan('laravel-api:generate-controller', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        unlink('tests/Data/Output/'.'ExampleController.php');
    }

    /** @test */
    public function it_generates_an_api_controller_without_override()
    {
        $this->artisan('laravel-api:generate-controller', ['name' => 'Example']);
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        unlink('tests/Data/Output/'.'ExampleController.php');
    }
}

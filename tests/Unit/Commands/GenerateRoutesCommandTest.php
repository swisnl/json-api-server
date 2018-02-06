<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 12:25.
 */

namespace Swis\LaravelApi\Console\Commands;

use Tests\CommandTestCase;

class GenerateRoutesCommandTest extends CommandTestCase
{
    /** @test */
    public function it_generates_routes()
    {
        $this->artisan('laravel-api:generate-routes', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.routes') . 'ExampleRoutes.php'));
        unlink('tests/Data/Output/' . 'ExampleRoutes.php');
    }
}

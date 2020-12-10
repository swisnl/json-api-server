<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:08.
 */

namespace Swis\JsonApi\Server\Console\Commands;

use Tests\CommandTestCase;

class GenerateModelCommandTest extends CommandTestCase
{
    /** @test */
    public function itGeneratesAModel()
    {
        $this->artisan('json-api-server:generate-model', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.model').'Example.php'));
        unlink('tests/Data/Output/'.'Example.php');
    }
}

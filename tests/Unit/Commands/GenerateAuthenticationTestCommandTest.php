<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:11.
 */

namespace Swis\JsonApi\Server\Console\Commands;

use Tests\CommandTestCase;

class GenerateAuthenticationTestCommandTest extends CommandTestCase
{
    /** @test */
    public function itGeneratesAnAuthenticationTest()
    {
        $this->artisan('json-api-server:generate-test', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.auth_test').'ExampleAuthenticationTest.php'));
        unlink('tests/Data/Output/'.'ExampleAuthenticationTest.php');
    }
}

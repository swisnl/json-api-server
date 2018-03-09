<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:32.
 */

namespace Swis\JsonApi\Server\Console\Commands;

use Tests\CommandTestCase;

class GenerateAllCommandTest extends CommandTestCase
{
    /** @test */
    public function it_generates_all_files()
    {
        $this->artisan('json-api-server:generate-all', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.repository').'ExampleRepository.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.model').'Example.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.policy').'ExamplePolicy.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.model_permissions').'ExamplePermissions.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.auth_test').'ExampleAuthenticationTest.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.routes').'ExampleRoutes.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.translation').'ExampleTranslation.php'));
    }

    /** @test */
    public function it_generates_all_files_except_translation()
    {
        $this->artisan('json-api-server:generate-all', ['name' => 'Example', '--path' => 'tests/Data/Output/', '--skip' => 'translation']);
        $this->assertTrue(file_exists(config('laravel_api.path.repository').'ExampleRepository.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.model').'Example.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.policy').'ExamplePolicy.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.model_permissions').'ExamplePermissions.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.auth_test').'ExampleAuthenticationTest.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.routes').'ExampleRoutes.php'));
        $this->assertFalse(file_exists(config('laravel_api.path.translation').'ExampleTranslation.php'));
    }
}

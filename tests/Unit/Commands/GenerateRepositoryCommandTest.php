<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 6-2-2018
 * Time: 13:23.
 */

namespace Swis\JsonApi\Server\Console\Commands;

use Tests\CommandTestCase;

class GenerateRepositoryCommandTest extends CommandTestCase
{
    /** @test */
    public function it_generates_a_repository()
    {
        $this->artisan('laravel-api:generate-repository', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.repository').'ExampleRepository.php'));
        unlink('tests/Data/Output/'.'ExampleRepository.php');
    }
}

<?php

namespace Tests\Unit;

use Swis\LaravelApi\Console\Commands\BaseGenerateCommand;
use Swis\LaravelApi\Services\CustomFileGenerator;
use Tests\TestCase;

class CustomFileGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_all_desired_custom_api_files()
    {
        $modelName = 'Example';
        $path = 'tests/Data/Output/';
        $templatesDir = 'resources/templates/';

        config(['laravel_api.path.controller' => $path]);
        config(['laravel_api.path.model' => $path]);
        config(['laravel_api.path.repository' => $path.'Repositories/']);
        config(['laravel_api.path.policy' => $path.'policies/']);
        config(['laravel_api.path.schema' => $path.'schemas/']);
        config(['laravel_api.path.translation' => $path]);

        config(['laravel_api.path.templates' => $templatesDir]);

        $generator = new CustomFileGenerator();
        $commandMock = $this->createMock(BaseGenerateCommand::class);
        $generator->setModelName($modelName);

        $generator->generate('Controller', 'controller', $path, $commandMock);
        $generator->generate('', 'model', $path, $commandMock);
        $generator->generate('Repository', 'repository', $path.'Repositories/', $commandMock);
        $generator->generate('Policy', 'policy', $path.'policies/', $commandMock);
        $generator->generate('Translation', 'translation', $path, $commandMock);

        $this->assertTrue(file_exists(config('laravel_api.path.controller').'ExampleController.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.model').'Example.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.repository').'ExampleRepository.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.policy').'ExamplePolicy.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.translation').'ExampleTranslation.php'));

        // Rolls back creations
        unlink(config('laravel_api.path.controller').'ExampleController.php');
        unlink(config('laravel_api.path.model').'Example.php');
        unlink(config('laravel_api.path.repository').'ExampleRepository.php');
        unlink(config('laravel_api.path.policy').'ExamplePolicy.php');
        unlink(config('laravel_api.path.translation').'ExampleTranslation.php');

        rmdir(config('laravel_api.path.repository'));
        rmdir(config('laravel_api.path.policy'));
    }
}

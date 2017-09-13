<?php

namespace Tests\Unit;

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

        config(['infyom.laravel_generator.path.policy' => $path.'policies/']);
        config(['infyom.laravel_generator.path.schema' => $path.'schemas/']);
        config(['infyom.laravel_generator.path.translation' => $path]);
        config(['infyom.laravel_generator.path.templates_dir' => $templatesDir]);

        $generator = new CustomFileGenerator();
        $generator->setModelName($modelName);
        $generator->generateSchema();
        $generator->generatePolicy();
        $generator->generateTranslation();

        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.policy') . 'ExamplePolicy.php'));
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.schema') . 'ExampleSchema.php'));
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.translation') . 'ExampleTranslation.php'));

        // Rolls back creations
        unlink(config('infyom.laravel_generator.path.policy') . 'ExamplePolicy.php');
        unlink(config('infyom.laravel_generator.path.schema') . 'ExampleSchema.php');
        unlink(config('infyom.laravel_generator.path.translation') . 'ExampleTranslation.php');

        rmdir(config('infyom.laravel_generator.path.policy'));
        rmdir(config('infyom.laravel_generator.path.schema'));
    }
}

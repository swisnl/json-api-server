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

        config(['laravel_api.path.policy' => $path.'policies/']);
        config(['laravel_api.path.schema' => $path.'schemas/']);
        config(['laravel_api.path.translation' => $path]);
        config(['laravel_api.path.templates' => $templatesDir]);

        $generator = new CustomFileGenerator();
        $generator->setModelName($modelName);
        $generator->generate('Schema', 'schema', $path.'schemas/');
        $generator->generate('Policy', 'policy', $path.'policies/');
        $generator->generate('Translation', 'translation', $path);

        $this->assertTrue(file_exists(config('laravel_api.path.policy').'ExamplePolicy.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.schema').'ExampleSchema.php'));
        $this->assertTrue(file_exists(config('laravel_api.path.translation').'ExampleTranslation.php'));

        // Rolls back creations
        unlink(config('laravel_api.path.policy').'ExamplePolicy.php');
        unlink(config('laravel_api.path.schema').'ExampleSchema.php');
        unlink(config('laravel_api.path.translation').'ExampleTranslation.php');

        rmdir(config('laravel_api.path.policy'));
        rmdir(config('laravel_api.path.schema'));
    }
}

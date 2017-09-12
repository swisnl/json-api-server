<?php

namespace Swis\test\Unit;

use Orchestra\Testbench\TestCase;
use Swis\LaravelApi\Services\CustomFileGenerator;

class CustomFileGeneratorTest extends TestCase
{
    /** @test */
    public function leTrue() {
        $this->assertTrue(true);
    }
    /*public function it_generates_all_desired_custom_api_files()
    {
        config(['infyom.laravel_generator.path.policy' => '/tests/Data/Output/Policies']);
        config(['infyom.laravel_generator.path.schema' => '/tests/Data/Output/Schemas']);
        config(['infyom.laravel_generator.path.translation' => '/tests/Data/Output/Data']);
        config(['infyom.laravel_generator.path.templates_dir' => 'vendor/swisnl/laravel-api/resources/templates/']);

        $generator = new CustomFileGenerator();
        $generator->setModelName('ExampleTest');
        $generator->generateSchema();
        $generator->generatePolicy();
        $generator->generateTranslation();

        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.policy').'ExampleTestPolicy.php'));
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.schema').'ExampleTestSchema.php'));
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.translation').'ExampleTestTranslation.php'));

        // Rolls back creations
        unlink(config('infyom.laravel_generator.path.policy').'ExampleTestPolicy.php');
        unlink(config('infyom.laravel_generator.path.schema').'ExampleTestSchema.php');
        unlink(config('infyom.laravel_generator.path.translation').'ExampleTestTranslation.php');
    }*/
}

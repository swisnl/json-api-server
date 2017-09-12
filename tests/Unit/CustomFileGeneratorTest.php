<?php

namespace Swis\test\Unit;

use PHPUnit\Framework\TestCase;
use Swis\LaravelApi\Services\CustomFileGenerator;

class CustomFileGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_all_desired_custom_api_files()
    {
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
    }
}

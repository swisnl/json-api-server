<?php

namespace Tests;

class CommandTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        config(['infyom.laravel_generator.path.templates_dir' => 'resources/templates/']);
    }

    /** @test */
    public function it_generates_a_schema()
    {
        $this->artisan('laravel-api:generate-schema', ['model' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.schema') . 'ExampleSchema.php'));
        unlink('tests/Data/Output/' . 'ExampleSchema.php');
    }

    /** @test */
    public function it_generates_a_policy()
    {
        $this->artisan('laravel-api:generate-policy', ['model' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.policy') . 'ExamplePolicy.php'));
        unlink('tests/Data/Output/' . 'ExamplePolicy.php');
    }

    /** @test */
    public function it_generates_a_translation()
    {
        $this->artisan('laravel-api:generate-translation', ['model' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('infyom.laravel_generator.path.translation') . 'ExampleTranslation.php'));
        unlink('tests/Data/Output/' . 'ExampleTranslation.php');
    }
}

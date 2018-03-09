<?php

namespace Tests;

class GenerateTranslationCommandTest extends CommandTestCase
{
    /** @test */
    public function it_generates_a_translation()
    {
        $this->artisan('json-api-server:generate-translation', ['name' => 'Example', '--path' => 'tests/Data/Output/']);
        $this->assertTrue(file_exists(config('laravel_api.path.translation').'ExampleTranslation.php'));
        unlink('tests/Data/Output/'.'ExampleTranslation.php');
    }
}

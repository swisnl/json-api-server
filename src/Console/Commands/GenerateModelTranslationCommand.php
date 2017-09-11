<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateModelTranslationCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-translation {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a translation for a model';

    protected $name;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateTranslation();
    }

    public function getModelName()
    {
        return $this->argument('name');
    }
}
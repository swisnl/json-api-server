<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateMigrationCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-migration {model} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an API controller.';

    protected $name;

    protected $overridePath;

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
        $this->name = $this->argument('model');
        $this->overridePath = $this->option('path');

        $this->call('infyom:migration', ['model' => $this->name]);
    }

    public function getModelName()
    {
        return $this->name;
    }

    public function getOverridePath()
    {
        return $this->overridePath;
    }

    public function getConfigPath()
    {
        return 'infyom.laravel_generator.path.migration';
    }
}

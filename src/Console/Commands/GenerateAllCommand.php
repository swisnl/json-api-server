<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateAllCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-all {model} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
        'Creates the following classes with implementation: Model, Controller, BaseApiRepository, Schema, Tests';

    protected $modelName;

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
        $this->modelName = $this->argument('model');
        $this->overridePath = $this->option('path');

        $this->overrideArtisanCallPaths();

        $this->call('infyom:api', [
            'model' => $this->modelName,
            '--skip' => 'requests, api_requests, routes, api_routes, test_trait',
        ]);

        $this->call('laravel-api:generate-schema', ['model' => $this->modelName, '--path' => $this->getOverridePath()]);
        $this->call('laravel-api:generate-translation', ['model' => $this->modelName, '--path' => $this->getOverridePath()]);
        $this->call('laravel-api:generate-policy', ['model' => $this->modelName, '--path' => $this->getOverridePath()]);

        $this->renameController();
    }

    protected function overrideArtisanCallPaths()
    {
        $overridePath = $this->getOverridePath();
        if (!isset($overridePath)) {
            return;
        }

        config(['infyom.laravel_generator.path.api_controller' => $overridePath]);
        config(['infyom.laravel_generator.path.model' => $overridePath]);
        config(['infyom.laravel_generator.path.repository' => $overridePath]);
        config(['infyom.laravel_generator.path.migration' => $overridePath]);

        config(['infyom.laravel_generator.path.api_test' => $overridePath]);
        config(['infyom.laravel_generator.path.repository_test' => $overridePath]);
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function getOverridePath()
    {
        return $this->overridePath;
    }

    public function getConfigPath()
    {
    }
}

<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateAllCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-all {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
        'Creates the following classes with implementation: Model, Controller, BaseApiRepository, Schema, Tests';

    protected $modelName;

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
        $this->call('infyom:api', [
            'model' => $this->modelName,
            '--skip' => 'requests, api_requests, routes, api_routes',
        ]);
        $this->call('laravel-api:generate-schema', ['name' => $this->modelName]);
        $this->call('laravel-api:generate-translation', ['name' => $this->modelName]);
        $this->call('laravel-api:generate-policy', ['name' => $this->modelName]);

        $this->renameController();
    }

    public function getModelName()
    {
        return $this->modelName;
    }
}

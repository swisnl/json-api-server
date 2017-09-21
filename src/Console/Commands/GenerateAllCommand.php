<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateAllCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-all {name} {--path=} {--skip=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
        'Creates the following classes with implementation: Model, Controller, BaseApiRepository, Schema';

    protected $modelName;

    protected $overridePath;

    protected $callsToSkip;

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
        $this->modelName = $this->argument('name');
        $this->overridePath = $this->option('path');
        $this->callsToSkip = explode(',', $this->option('skip'));

        $this->makeGeneratorCalls();
    }

    public function makeGeneratorCalls()
    {
        $skip = $this->callsToSkip;

        if (!in_array('controller', $skip)) {
            $this->call('laravel-api:generate-controller', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }

        if (!in_array('model', $skip)) {
            $this->call('laravel-api:generate-model', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }

        if (!in_array('repository', $skip)) {
            $this->call('laravel-api:generate-repository', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }

        if (!in_array('schema', $skip)) {
            $this->call('laravel-api:generate-schema', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }

        if (!in_array('translation', $skip)) {
            $this->call('laravel-api:generate-translation', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }

        if (!in_array('policy', $skip)) {
            $this->call('laravel-api:generate-policy', ['name' => $this->modelName, '--path' => $this->overridePath]);
        }
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

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
        $generatorCalls = [
            'controller' => 'laravel-api:generate-controller',
            'model' => 'laravel-api:generate-model',
            'model-permissions' => 'laravel-api:generate-model-permissions',
            'repository' => 'laravel-api:generate-repository',
            'translation' => 'laravel-api:generate-translation',
            'policy' => 'laravel-api:generate-policy',
            'test' => 'laravel-api:generate-test',
        ];

        foreach ($generatorCalls as $type => $generatorCall) {
            if (in_array($type, $this->callsToSkip)) {
                continue;
            }
            $this->call($generatorCall, ['name' => $this->modelName, '--path' => $this->overridePath]);
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

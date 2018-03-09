<?php

namespace Swis\JsonApi\Server\Console\Commands;

use Illuminate\Console\Command;

class GenerateAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json-api-server:generate-all {name} {--path=} {--skip=}';

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
            'controller' => 'json-api-server:generate-controller',
            'model' => 'json-api-server:generate-model',
            'model-permissions' => 'json-api-server:generate-model-permissions',
            'repository' => 'json-api-server:generate-repository',
            'translation' => 'json-api-server:generate-translation',
            'policy' => 'json-api-server:generate-policy',
            'test' => 'json-api-server:generate-test',
            'routes' => 'json-api-server:generate-routes',
        ];

        foreach ($generatorCalls as $type => $generatorCall) {
            if (in_array($type, $this->callsToSkip)) {
                continue;
            }
            $this->call($generatorCall, ['name' => $this->modelName, '--path' => $this->overridePath]);
        }
    }
}

<?php

namespace Swis\LaravelApi\Console\Commands;

use Swis\LaravelApi\Services\CustomFileGenerator;
use Illuminate\Console\Command;

class GenerateApiClasses extends Command
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
    protected $description = 'Creates the following classes with implementation: Model, Controller, Repository, Schema, Tests';

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
        $this->generateFiles();
    }

    protected function generateFiles()
    {
        $this->call('infyom:api', ['model' => $this->modelName, '--skip' => 'requests, api_requests, routes, api_routes']);

        $generator = new CustomFileGenerator($this->modelName);
        $generator->generateAll();
        $this->renameFiles();
    }

    protected function renameFiles()
    {
        $newControllerName = $this->generateNewControllerName();

        if (file_exists($newControllerName)) {
            $this->error('Auto generated files already exist - GenerateApiClasses.php, renameFiles()');
            return $this;
        }

        rename(config('infyom.laravel_generator.path.api_controller').$this->modelName.'APIController.php', $newControllerName);

        return $this;
    }

    protected function generateNewModelName(): string
    {
        return 'app/' . $this->modelName . '/' . $this->modelName . '.php';
    }

    protected function generateNewRepositoryName(): string
    {
        return 'app/' . $this->modelName . '/' . $this->modelName . 'Repository.php';
    }

    protected function generateNewControllerName(): string
    {
        return 'app/Http/Controllers/Api/' . $this->modelName . 'Controller.php';
    }
}

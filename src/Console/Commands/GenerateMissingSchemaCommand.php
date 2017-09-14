<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateMissingSchemaCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-missing-schemas {model} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates all not existing schemas based on relationships in the models';

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

        $pathToFolder = $this->generatePathToFolder();
        $pathToSchemas = $this->generatePathToSchemaFolder();
        $pathToRepositoryClass = $this->generatePathToRepositoryClassName($pathToFolder);

        if (!class_exists($pathToRepositoryClass)) {
            return;
        }

        $repository = app()->make($pathToRepositoryClass);
        $relationships = $repository->getModelRelationships();

        foreach ($relationships as $relationship) {
            $schema = $this->generateSchemaClassName($relationship);
            if (file_exists($pathToSchemas.$schema) ||
                file_exists($pathToFolder.$schema)) {
                continue;
            }

            $this->modelName = $schema;
            $this->generateSchema();
        }
    }

    protected function generatePathToFolder(): string
    {
        return 'App\\'.$this->modelName.'\\';
    }

    protected function generatePathToSchemaFolder(): string
    {
        return 'App/JsonSchemas/';
    }

    protected function generatePathToRepositoryClassName($pathToFolder): string
    {
        $model = app()->make($pathToFolder.$this->modelName);

        if (!file_exists($model)) {
            return $pathToFolder.$this->modelName.'BaseApiRepository';
        }

        $modelRepository = $model->repository;

        if (null !== $modelRepository) {
            return $modelRepository;
        }

        return 'Not Found';
    }

    protected function generateSchemaClassName($relationship): string
    {
        return ucfirst(str_singular($relationship));
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function getOverridePath()
    {
        $this->overridePath;
    }

    public function getConfigPath()
    {
        return 'laravel_api_config.path.schema';
    }
}

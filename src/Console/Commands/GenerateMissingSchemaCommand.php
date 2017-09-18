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

        $this->overridePath();

        $model = app()->make($this->modelName);
        $repository = app()->make($model->repository);

        $relationships = $repository->getModelRelationships();

        foreach ($relationships as $relationship) {
            $schema = $this->generateSchemaClassName($relationship);
            if (file_exists($this->getConfigPath().$schema)) {
                continue;
            }

            $this->modelName = $schema;
            $this->generateSchema();
        }
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
        return $this->overridePath;
    }

    public function getConfigPath()
    {
        return 'laravel_api_config.path.schema';
    }
}

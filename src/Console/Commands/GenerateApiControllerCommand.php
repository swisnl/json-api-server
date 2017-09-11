<?php

namespace Swis\LaravelApi\Console\Commands;

class GenerateApiControllerCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an API controller.';

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
        $this->name = $this->argument('name');
        $this->call('infyom.api:controller', ['model' => $this->name]);
        $this->renameController();
    }

    public function getModelName()
    {
        return $this->name;
    }
}
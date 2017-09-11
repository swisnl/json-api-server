<?php

namespace Swis\LaravelApi\Console\Commands;

use Illuminate\Console\Command;

class GenerateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a repository for a model';

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
        $this->call('infyom:repository', ['model' => $this->name]);
    }

}
<?php

namespace Swis\LaravelApi\Console\Commands;

use Illuminate\Console\Command;

class GenerateMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-migration {name}';

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
        $this->call('infyom:migration', ['model' => $this->name]);
    }
}
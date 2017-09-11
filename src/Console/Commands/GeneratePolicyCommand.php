<?php
/**
 * Created by PhpStorm.
 * User: ddewit
 * Date: 11-9-2017
 * Time: 15:21
 */

namespace Swis\LaravelApi\Console\Commands;

class GeneratePolicyCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-policy {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a policy for a model';

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
        $this->generatePolicy();
    }

    public function getModelName()
    {
        return $this->argument('name');
    }
}
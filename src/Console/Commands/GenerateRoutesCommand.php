<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 5-2-2018
 * Time: 17:08.
 */

namespace Swis\LaravelApi\Console\Commands;

class GenerateRoutesCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-api:generate-routes {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates routes for a controller';

    protected $name;

    protected $overridePath;

    public function handle()
    {
        $this->name = $this->argument('name');
        $this->overridePath = $this->option('path');
        $this->overridePath();
        $this->generateClass('Routes', 'route');
    }

    public function getModelName()
    {
        return $this->name;
    }

    public function getOverridePath()
    {
        return $this->overridePath;
    }

    public function getConfigPath()
    {
        return 'laravel_api.path.routes';
    }
}

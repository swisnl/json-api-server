<?php

namespace Swis\JsonApi\Server\Console\Commands;

class GenerateAuthenticationTestCommand extends BaseGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json-api-server:generate-test {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates tests for your endpoints.';

    protected $name;

    protected $overridePath;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->name = $this->argument('name');
        $this->overridePath = $this->option('path');
        $this->overridePath();
        $this->generateClass('AuthenticationTest', 'authentication_test');
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
        return 'laravel_api.path.auth_test';
    }
}

<?php

namespace Swis\LaravelApi\Console\Commands;

use Illuminate\Console\Command;
use Swis\LaravelApi\Services\CustomFileGenerator;

abstract class BaseGenerateCommand extends Command
{
    protected $generator;

    public function __construct()
    {
        parent::__construct();
        $this->generator = new CustomFileGenerator();
    }

    protected function generateSchema()
    {
        $this->generator->setModelName($this->getModelName())->generateSchema();
        $this->info('Schema generated in ' . $this->getConfigPath());
    }

    protected function generateTranslation()
    {
        $this->generator->setModelName($this->getModelName())->generateTranslation();
        $this->info('ModelTranslation generated in ' . $this->getConfigPath());
    }

    protected function generatePolicy()
    {
        $this->generator->setModelName($this->getModelName())->generatePolicy();
        $this->info('Policy generated in ' . $this->getConfigPath());
    }

    protected function generateNewControllerName(): string
    {
        return config('infyom.laravel_generator.path.api_controller').$this->getModelName().'Controller.php';
    }

    protected function renameController()
    {
        $newControllerName = $this->generateNewControllerName();

        if (file_exists($newControllerName)) {
            $this->error('Auto generated files already exist - GenerateAllCommand.php, renameFiles()');

            return $this;
        }

        rename(config('infyom.laravel_generator.path.api_controller').$this->getModelName()
            .'APIController.php', $newControllerName);

        return $this;
    }

    protected function overridePath()
    {
        $overridePath = $this->getOverridePath();
        $configPath = $this->getConfigPath();

        if (!isset($overridePath) || !isset($configPath)) {
            return;
        }

        config([$configPath => $overridePath]);
    }

    abstract public function getModelName();

    abstract public function getOverridePath();

    abstract public function getConfigPath();
}

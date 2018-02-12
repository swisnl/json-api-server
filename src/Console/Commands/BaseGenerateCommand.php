<?php

namespace Swis\JsonApi\Server\Console\Commands;

use Illuminate\Console\Command;
use Swis\JsonApi\Server\Services\CustomFileGenerator;

abstract class BaseGenerateCommand extends Command
{
    protected $generator;

    public function __construct()
    {
        parent::__construct();
        $this->generator = new CustomFileGenerator();
    }

    protected function generateClass($classType, $stubName)
    {
        $configPath = config($this->getConfigPath());
        $this->generator->setModelName($this->getModelName())->generate($classType, $stubName, $configPath, $this);
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

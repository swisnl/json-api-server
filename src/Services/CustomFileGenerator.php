<?php

namespace Swis\LaravelApi\Services;

use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\TemplateUtil;

class CustomFileGenerator extends BaseGenerator
{
    private $modelName;
    private $dynamicVars;

    public function __construct()
    {
        $this->generateNewDir();
    }

    public function generateSchema()
    {
        $this->generate('Schema', 'schema', config('laravel_api_config.path.schema'));
    }

    public function generateTranslation()
    {
        $this->generate('Translation', 'model_translation', config('laravel_api_config.path.translation'));
    }

    public function generatePolicy()
    {
        $this->generate('Policy', 'policy', config('laravel_api_config.path.policy'));
    }

    protected function generate($classExtensionName, $stubName, $path)
    {
        if (file_exists($path . $this->modelName . $classExtensionName . '.php')) {
            return;
        }

        $this->setDynamicVars();

        $templateData = TemplateUtil::getTemplate($stubName, 'laravel-generator');
        $templateData = TemplateUtil::fillTemplate($this->dynamicVars, $templateData);
        FileUtil::createFile($path, $this->modelName . $classExtensionName . '.php', $templateData);
    }

    protected function setDynamicVars()
    {
        $this->dynamicVars = [
            '$MODEL_NAME$' => $this->modelName,
            '$NAMESPACE_MODEL$' => config('infyom.laravel_generator.namespace.model'),
            '$NAMESPACE_MODEL_EXTEND$' => config('infyom.laravel_generator.model_extend_class'),
            '$NAME_SPACE_REPOSITORY$' => config('infyom.laravel_generator.namespace.repository'),
            '$NAMESPACE_SCHEMA$' => config('laravel_api_config.namespace.schema'),
            '$NAME_SPACE_POLICY$' => config('laravel_api_config.namespace.policy'),
            '$FIELDS$' => '', //TODO,
        ];

        return $this;
    }

    protected function generateNewDir()
    {
        $schemaPath = config('laravel_api_config.path.schema');
        if (!is_dir($schemaPath)) {
            mkdir($schemaPath);
        }

        $policyPath = config('laravel_api_config.path.policy');
        if (!is_dir($policyPath)) {
            mkdir($policyPath);
        }

        return $this;
    }

    public function setModelName($modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }
}

<?php

namespace Swis\LaravelApi\Services;

use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;
use InfyOm\Generator\Utils\TemplateUtil;

class CustomFileGenerator extends BaseGenerator
{
    private $modelName;

    private $dynamicVars;

    public function __construct($modelName)
    {
        $this->modelName = $modelName;
        $this->setDynamicVars();
        $this->generateNewDir();
    }

    public function generateAll()
    {
        $this->generate('Schema', 'schema', config('infyom.laravel_generator.path.schema'));
        $this->generate('Translation', 'model_translation', config('infyom.laravel_generator.path.translation'));
        $this->generate('Policy', 'policy', config('infyom.laravel_generator.path.policy'));
    }

    public function generateSchema($path)
    {
        $templateData = TemplateUtil::getTemplate('schema', 'laravel-generator');
        $templateData = TemplateUtil::fillTemplate($this->dynamicVars, $templateData);
        FileUtil::createFile($path, $this->modelName.'Schema.php', $templateData);
    }

    protected function generate($classExtensionName, $stubName, $path)
    {
        $templateData = TemplateUtil::getTemplate($stubName, 'laravel-generator');
        $templateData = TemplateUtil::fillTemplate($this->dynamicVars, $templateData);
        FileUtil::createFile($path, $this->modelName.$classExtensionName.'.php', $templateData);
    }

    protected function setDynamicVars() {
        $this->dynamicVars = [
            '$MODEL_NAME$' => $this->modelName,
            '$NAMESPACE_MODEL$' => config('infyom.laravel_generator.namespace.model'),
            '$NAMESPACE_MODEL_EXTEND$' => config('infyom.laravel_generator.model_extend_class'),
            '$NAMESPACE_SCHEMA$' => config('infyom.laravel_generator.namespace.schema'),
            '$NAME_SPACE_REPOSITORY$' => config('infyom.laravel_generator.namespace.repository'),
            '$FIELDS$' => '', //TODO,
        ];
        return $this;
    }

    protected function generateNewDir()
    {
        if (!file_exists(config('infyom.laravel_generator.path.schema'))) {
            mkdir(config('infyom.laravel_generator.path.schema'));
        }

        if (!file_exists(config('infyom.laravel_generator.path.policy'))) {
            mkdir(config('infyom.laravel_generator.path.policy'));
        }
        return $this;
    }
}

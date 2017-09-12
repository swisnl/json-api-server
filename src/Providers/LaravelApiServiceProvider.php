<?php

namespace Swis\LaravelApi\Providers;

use Collective\Html\HtmlServiceProvider;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use InfyOm\AdminLTETemplates\AdminLTETemplatesServiceProvider;
use InfyOm\Generator\InfyOmGeneratorServiceProvider;
use Laracasts\Flash\FlashServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Swis\LaravelApi\Console\Commands\GenerateAllCommand;
use Swis\LaravelApi\Console\Commands\GenerateApiControllerCommand;
use Swis\LaravelApi\Console\Commands\GenerateMigrationCommand;
use Swis\LaravelApi\Console\Commands\GenerateMissingSchemaCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelSchemaCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelTranslationCommand;
use Swis\LaravelApi\Console\Commands\GeneratePolicyCommand;
use Swis\LaravelApi\Console\Commands\GenerateRepositoryCommand;
use Swis\LaravelApi\Http\Middleware\ConfigureLocale;
use Swis\LaravelApi\Http\Middleware\PermissionMiddleware;

class LaravelApiServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('route_permission_middleware', PermissionMiddleware::class);
        $router->aliasMiddleware('configure-locale', ConfigureLocale::class);
    }

    public function register()
    {
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(TranslatableServiceProvider::class);
        $this->app->register(FlashServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(InfyOmGeneratorServiceProvider::class);
        $this->app->register(AdminLTETemplatesServiceProvider::class);
        $this->app->register(htmlServiceProvider::class);

        $this->commands([
            GenerateAllCommand::class,
            GenerateMissingSchemaCommand::class,
            GenerateApiControllerCommand::class,
            GenerateModelCommand::class,
            GenerateModelSchemaCommand::class,
            GenerateModelTranslationCommand::class,
            GeneratePolicyCommand::class,
            GenerateRepositoryCommand::class,
            GenerateMigrationCommand::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel_generator.php',
            'infyom.laravel_generator'
        );
    }
}

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
use Swis\LaravelApi\Console\Commands\GenerateApiClasses;
use Swis\LaravelApi\Console\Commands\GenerateSchemasCommand;
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
           GenerateApiClasses::class,
           GenerateSchemasCommand::class,
        ]);
        
        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel_generator.php', 'infyom.laravel_generator'
        );
    }
}
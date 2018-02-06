<?php

namespace Swis\LaravelApi\Providers;

use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Swis\LaravelApi\Console\Commands\GenerateAllCommand;
use Swis\LaravelApi\Console\Commands\GenerateApiControllerCommand;
use Swis\LaravelApi\Console\Commands\GenerateAuthenticationTestCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelPermissionsCommand;
use Swis\LaravelApi\Console\Commands\GenerateModelTranslationCommand;
use Swis\LaravelApi\Console\Commands\GeneratePolicyCommand;
use Swis\LaravelApi\Console\Commands\GenerateRepositoryCommand;
use Swis\LaravelApi\Console\Commands\GenerateRoutesCommand;
use Swis\LaravelApi\Http\Middleware\ConfigureLocale;
use Swis\LaravelApi\Http\Middleware\InspectContentType;
use Swis\LaravelApi\Http\Middleware\PermissionMiddleware;
use Symfony\Component\Finder\Finder;

class LaravelApiServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('route_permission_middleware', PermissionMiddleware::class);
        $router->aliasMiddleware('configure_locale', ConfigureLocale::class);
        $router->aliasMiddleware('inspect_content_type', InspectContentType::class);

        $this->publishes([
            __DIR__.'/../../config/laravel_api.php' => base_path('config/laravel_api.php'),
        ], 'laravel-api');

        $this->publishes([
            __DIR__.'/../../resources/templates' => base_path('resources/templates'),
        ], 'laravel-api-templates');
        $this->mapJsonApiRoutes();
    }

    public function register()
    {
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(TranslatableServiceProvider::class);

        $this->commands([
            GenerateAllCommand::class,
            GenerateApiControllerCommand::class,
            GenerateModelCommand::class,
            GenerateModelTranslationCommand::class,
            GeneratePolicyCommand::class,
            GenerateRepositoryCommand::class,
            GenerateModelPermissionsCommand::class,
            GenerateAuthenticationTestCommand::class,
            GenerateRoutesCommand::class,
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/laravel_api.php',
            'laravel_api'
        );
    }

    protected function mapJsonApiRoutes()
    {
        if (!File::exists(config('laravel_api.path.routes'))) {
            return;
        }
        $files = Finder::create()->files()->in(config('laravel_api.path.routes'));
        foreach ($files as $file) {
            Route::middleware('inspect_content_type')->group($file->getRealPath());
        }
    }
}

<?php

return [
    // Generator configuration
    'path' => [
        'model' => app_path('/'),

        'model_permissions' => app_path('Permissions'),

        'translation' => app_path('/'),

        'controller' => app_path('Http/Controllers/Api/'),

        'repository' => app_path('Repositories/'),

        'policy' => app_path('Policies/'),

        'migration' => base_path('database/migrations'),

        'templates' => 'vendor/swisnl/laravel-api/resources/templates/',
    ],

    'namespace' => [
        'model' => 'App',

        'model_permissions' => 'App',

        'controller' => 'App\Http\Controllers\Api',

        'repository' => 'App\Repositories',

        'translation' => 'App',

        'policy' => 'App\Policies'
    ],

    // Permissions configuration
    'checkForPermissions' => true
];
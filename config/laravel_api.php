<?php

return [
    // Generator configuration
    'path' => [
        'model' => app_path('/'),

        'translation' => app_path('/'),

        'controller' => app_path('Http/Controllers/Api/'),

        'repository' => app_path('Repositories/'),

        'schema' => app_path('Schemas/'),

        'policy' => app_path('Policies/'),

        'templates' => 'vendor/swisnl/laravel-api/resources/templates/',
    ],

    'namespace' => [
        'model' => 'App',

        'controller' => 'App\Http\Controllers\Api',

        'repository' => 'App\Repositories',

        'translation' => 'App',

        'schema' => 'App\Schemas',

        'policy' => 'App\Policies'
    ],

    // Permissions configuration
    'checkForPermissions' => true
];
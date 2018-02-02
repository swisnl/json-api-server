<?php

return [
    // Generator configuration
    'path' => [
        'model' => app_path('/'),

        'model_permissions' => app_path('Permissions/'),

        'translation' => app_path('Translations/'),

        'controller' => app_path('Http/Controllers/Api/'),

        'repository' => app_path('Repositories/'),

        'policy' => app_path('Policies/'),

        'auth_test' => base_path('tests/Authentication/'),

        'templates' => 'vendor/swisnl/laravel-api/resources/templates/',
    ],

    'namespace' => [
        'model' => 'App',

        'model_permissions' => 'App\Permissions',

        'controller' => 'App\Http\Controllers\Api',

        'repository' => 'App\Repositories',

        'translation' => 'App\Translations',

        'policy' => 'App\Policies',

        'auth_test' => 'App\Tests\Authentication'
    ],

    // Permissions configuration
    'checkForPermissions' => false,

    // Load all relationships to have response exactly like json api. This slows down the API immensely.
    'loadAllJsonApiRelationships' => true,
];
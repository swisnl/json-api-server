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

        'templates' => 'vendor/swisnl/json-api-server/resources/templates/',

        'routes' => app_path('Http/Routes/')
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
    'permissions' => [
        'checkDefaultIndexPermission' => false,

        'checkDefaultShowPermission' => false,

        'checkDefaultCreatePermission' => false,

        'checkDefaultUpdatePermission' => false,

        'checkDefaultDeletePermission' => false,
    ],


    // Load all relationships to have response exactly like json api. This slows down the API immensely.
    'loadAllJsonApiRelationships' => true,
];
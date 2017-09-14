<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    */

    'path' => [

        'model' => app_path('/'),

        'repository' => app_path('Repositories/'),

        'migration' => base_path('database/migrations/'),

        'datatables' => app_path('DataTables/'),

        'routes' => base_path('routes/api.php'),

        'api_routes' => base_path('routes/api.php'),

        'request' => app_path('Http/Requests/'),

        'api_request' => app_path('Http/Requests/API/'),

        'controller' => app_path('Http/Controllers/'),

        'api_controller' => app_path('Http/Controllers/Api/'),

        'test_trait' => base_path('tests/traits/'),

        'repository_test' => base_path('tests/'),

        'api_test' => base_path('tests/'),

        'views' => base_path('resources/views/'),

        'schema_files' => base_path('resources/model_schemas/'),

        'templates_dir' => base_path('vendor/swisnl/laravel-api/resources/templates/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => [

        'model' => 'App',

        'repository' => 'App\Repositories',

        'api_controller' => 'App\Http\Controllers\Api',

        'datatables' => 'App\DataTables',

        'controller' => 'App\Http\Controllers',

        'request' => 'App\Http\Requests',

        'api_request' => 'App\Http\Requests\API',
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    */

    'templates' => 'core-templates',

    /*
    |--------------------------------------------------------------------------
    | Model extend class
    |--------------------------------------------------------------------------
    |
    */

    'model_extend_class' => 'Illuminate\Database\Eloquent\Model',

    /*
    |--------------------------------------------------------------------------
    | API routes prefix & version
    |--------------------------------------------------------------------------
    |
    */

    'api_prefix' => 'api',

    'api_version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    */

    'options' => [

        'softDelete' => true,

        'tables_searchable_default' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Prefixes
    |--------------------------------------------------------------------------
    |
    */

    'prefixes' => [

        'route' => '',  // using admin will create route('admin.?.index') type routes

        'path' => '',

        'view' => '',  // using backend will create return view('backend.?.index') type the backend views directory

        'public' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Add-Ons
    |--------------------------------------------------------------------------
    |
    */

    'add_on' => [

        'swagger' => true,

        'tests' => true,

        'datatables' => false,

        'menu' => [

            'enabled' => false,

            'menu_file' => 'layouts/menu.blade.php',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Timestamp Fields
    |--------------------------------------------------------------------------
    |
    */

    'timestamps' => [

        'enabled' => true,

        'created_at' => 'created_at',

        'updated_at' => 'updated_at',

        'deleted_at' => 'deleted_at',
    ],

];

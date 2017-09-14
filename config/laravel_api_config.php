<?php

return [
    'path' => [
        'translation' => app_path('/'),

        'schema' => app_path('Schemas/'),

        'policy' => app_path('Policies/'),
    ],

    'namespace' => [
        'translation' => 'App',

        'schema' => 'App\JsonSchemas',

        'policy' => 'App\Policies'
    ],
];
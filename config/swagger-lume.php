<?php

return [
    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
         */
        'title' => 'Swagger Lumen API',
    ],

    'routes' => [
        /*
        |--------------------------------------------------------------------------
        | Route for accessing api documentation interface
        |--------------------------------------------------------------------------
         */
        'api' => '/documentation',

        /*
        |--------------------------------------------------------------------------
        | Route for accessing parsed swagger annotations.
        |--------------------------------------------------------------------------
         */
        'docs' => '/docs',

        /*
        |--------------------------------------------------------------------------
        | Route for Oauth2 authentication callback.
        |--------------------------------------------------------------------------
        */
        'oauth2_callback' => '/api/oauth2-callback',

        /*
        |--------------------------------------------------------------------------
        | Route for serving assets
        |--------------------------------------------------------------------------
        */
        'assets' => '/swagger-ui-assets',

        /*
        |--------------------------------------------------------------------------
        | Middleware allows to prevent unexpected access to API documentation
        |--------------------------------------------------------------------------
         */
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Absolute path to location where parsed swagger annotations will be stored
        |--------------------------------------------------------------------------
         */
        'docs' => storage_path('api-docs'),

        /*
        |--------------------------------------------------------------------------
        | File name of the generated json documentation file
        |--------------------------------------------------------------------------
        */
        'docs_json' => 'api-docs.json',

        /*
        |--------------------------------------------------------------------------
        | File name of the generated YAML documentation file
        |--------------------------------------------------------------------------
         */

        'docs_yaml' => 'api-docs.yaml',

        /*
        * Set this to `json` or `yaml` to determine which documentation file to use in UI
        */
        'format_to_use_for_docs' => env('SWAGGER_LUME__FORMAT_TO_USE_FOR_DOCS', 'json'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory containing the swagger annotations are stored.
        |--------------------------------------------------------------------------
         */
        'annotations' => base_path('app'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directories that you would like to exclude from swagger generation
        |--------------------------------------------------------------------------
         */
        'excludes' => [],

        /*
        |--------------------------------------------------------------------------
        | Edit to set the swagger scan base path
        |--------------------------------------------------------------------------
        */
        'base' => env('L5_SWAGGER_BASE_PATH', '/syed/public'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory where to export views
        |--------------------------------------------------------------------------
         */
        'views' => base_path('resources/views/vendor/swagger-lume'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API security definitions. Will be generated into documentation file.
    |--------------------------------------------------------------------------
    */
    'security' => [
        'jwt' => [
            'type' => 'apiKey',
            'description' => 'Enter token in format (Bearer {token})',
            'name' => 'Authorization',
            'in' => 'header',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Turn this off to remove swagger generation on production
    |--------------------------------------------------------------------------
     */
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', true),

    /*
    |--------------------------------------------------------------------------
    | Turn this on to generate a copy of documentation in yaml format
    |--------------------------------------------------------------------------
     */

    'generate_yaml_copy' => env('SWAGGER_LUME_GENERATE_YAML_COPY', false),

    /*
    |--------------------------------------------------------------------------
    | Edit to set the swagger version number
    |--------------------------------------------------------------------------
     */
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),

    /*
    |--------------------------------------------------------------------------
    | Edit to trust the proxy's ip address - needed for AWS Load Balancer
    |--------------------------------------------------------------------------
     */
    'proxy' => false,

    /*
    |--------------------------------------------------------------------------
    | Configs plugin allows to fetch external configs instead of passing them to SwaggerUIBundle.
    | See more at: https://github.com/swagger-api/swagger-ui#configs-plugin
    |--------------------------------------------------------------------------
    */

    'additional_config_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Apply a sort to the operation list of each API. It can be 'alpha' (sort by paths alphanumerically),
    | 'method' (sort by HTTP method).
    | Default is the order returned by the server unchanged.
    |--------------------------------------------------------------------------
    */

    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

    /*
    |--------------------------------------------------------------------------
    | Uncomment to pass the validatorUrl parameter to SwaggerUi init on the JS
    | side.  A null value here disables validation.
    |--------------------------------------------------------------------------
    */

    'validator_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Add constants which can be used in anotations
    |--------------------------------------------------------------------------
     */
    'constants' => [
        'SWAGGER_LUME_CONST_HOST' => env('SWAGGER_LUME_CONST_HOST', 'http://localhost:82'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Force assets to be loaded over HTTPS (Solves mixed content errors when application is behind a load balancer.)
    |--------------------------------------------------------------------------
     */
    'force_https' => env('SWAGGER_LUME_FORCE_HTTPS', false),
];

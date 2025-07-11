<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        'r2' => [
            'driver' => 's3',
            'key' => env('CLOUDFLARE_R2_ACCESS_KEY_ID'),
            'secret' => env('CLOUDFLARE_R2_SECRET_ACCESS_KEY'),
            'region' => 'us-east-1',
            'bucket' => env('CLOUDFLARE_R2_BUCKET'),
            'url' => env('CLOUDFLARE_R2_URL'),
            'visibility' => 'private',
            'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
            'use_path_style_endpoint' => env('CLOUDFLARE_R2_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        's1' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's2' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_2'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_2'),
            'region' => env('AWS_DEFAULT_REGION_2'),
            'bucket' => env('AWS_BUCKET_2'),
            'url' => env('AWS_URL_2'),
            'endpoint' => env('AWS_ENDPOINT_2'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_3'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_3'),
            'region' => env('AWS_DEFAULT_REGION_3'),
            'bucket' => env('AWS_BUCKET_3'),
            'url' => env('AWS_URL_3'),
            'endpoint' => env('AWS_ENDPOINT_3'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's4' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_4'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_4'),
            'region' => env('AWS_DEFAULT_REGION_4'),
            'bucket' => env('AWS_BUCKET_4'),
            'url' => env('AWS_URL_4'),
            'endpoint' => env('AWS_ENDPOINT_4'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's5' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_5'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_5'),
            'region' => env('AWS_DEFAULT_REGION_5'),
            'bucket' => env('AWS_BUCKET_5'),
            'url' => env('AWS_URL_5'),
            'endpoint' => env('AWS_ENDPOINT_5'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's6' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_6'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_6'),
            'region' => env('AWS_DEFAULT_REGION_6'),
            'bucket' => env('AWS_BUCKET_6'),
            'url' => env('AWS_URL_6'),
            'endpoint' => env('AWS_ENDPOINT_6'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's7' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_7'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_7'),
            'region' => env('AWS_DEFAULT_REGION_7'),
            'bucket' => env('AWS_BUCKET_7'),
            'url' => env('AWS_URL_7'),
            'endpoint' => env('AWS_ENDPOINT_7'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's8' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_8'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_8'),
            'region' => env('AWS_DEFAULT_REGION_8'),
            'bucket' => env('AWS_BUCKET_8'),
            'url' => env('AWS_URL_8'),
            'endpoint' => env('AWS_ENDPOINT_8'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's9' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_9'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_9'),
            'region' => env('AWS_DEFAULT_REGION_9'),
            'bucket' => env('AWS_BUCKET_9'),
            'url' => env('AWS_URL_9'),
            'endpoint' => env('AWS_ENDPOINT_9'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        's10' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID_10'),
            'secret' => env('AWS_SECRET_ACCESS_KEY_10'),
            'region' => env('AWS_DEFAULT_REGION_10'),
            'bucket' => env('AWS_BUCKET_10'),
            'url' => env('AWS_URL_10'),
            'endpoint' => env('AWS_ENDPOINT_10'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];

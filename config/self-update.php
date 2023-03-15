<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default source repository type
    |--------------------------------------------------------------------------
    |
    | The default source repository type you want to pull your updates from.
    |
    */

    'default' => env('SELF_UPDATER_SOURCE', 'github'),

    /*
    |--------------------------------------------------------------------------
    | Version installed
    |--------------------------------------------------------------------------
    |
    | Set this to the version of your software installed on your system.
    |
    */

    'version_installed' => env('APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Repository types
    |--------------------------------------------------------------------------
    |
    | A repository can be of different types, which can be specified here.
    | Current options:
    | - github
    | - http
    |
    */

    'repository_types' => [
        'github' => [
            'type' => 'github',
            'repository_vendor' => env('SELF_UPDATER_REPO_VENDOR', ''),
            'repository_name' => env('SELF_UPDATER_REPO_NAME', ''),
            'repository_url' =>  env('SELF_UPDATER_REPO_URL', ''),
            'download_path' => storage_path(),
            'private_access_token' => env('SELF_UPDATER_GITHUB_PRIVATE_ACCESS_TOKEN', ''),
        ],
        'http' => [
            'type' => 'http',
            'repository_url' => env('SELF_UPDATER_REPO_URL', ''),
            'pkg_filename_format' => env('SELF_UPDATER_PKG_FILENAME_FORMAT', 'v_VERSION_'),
            'download_path' => env('SELF_UPDATER_DOWNLOAD_PATH', '/tmp'),
            'private_access_token' => env('SELF_UPDATER_HTTP_PRIVATE_ACCESS_TOKEN', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude files from update
    |--------------------------------------------------------------------------
    |
    */

    'exclude_files' => [
        '.htaccess',
        'install.php',
        'public/skins/default/images/small-logo.png',
        'public/favicon.ico',
        'public/robots.txt',
        'resources/views/frontend/default/homepage/footer.blade.php',
        'resources/views/frontend/default/artist-management/claim.blade.php',
        'resources/views/frontend/default/homepage/footer.blade.php',
        'resources/views/frontend/default/landing/index.blade.php',
        'public/skins/default/css/custom.css',
    ],


    /*
    |--------------------------------------------------------------------------
    | Exclude folders from update
    |--------------------------------------------------------------------------
    |
    */

    'exclude_folders' => [
        'bootstrap/cache',
        'config',
        'public/common',
        'public/skins/default/images',
        'public/svg',
        'storage',
        'vendor',
        'resources/lang/es',
        'resources/lang/et',
        'resources/lang/fr',
        'resources/lang/ja',
        'resources/lang/ko',
        'resources/lang/vi',
        'resources/lang/zh',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Logging
    |--------------------------------------------------------------------------
    |
    | Configure if fired events should be logged
    |
    */

    'log_events' => env('SELF_UPDATER_LOG_EVENTS', false),

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Specify for which events you want to get notifications. Out of the box you can use 'mail'.
    |
    */

    'notifications' => [
        'notifications' => [
            \NiNaCoder\Updater\Notifications\Notifications\UpdateSucceeded::class => ['mail'],
            \NiNaCoder\Updater\Notifications\Notifications\UpdateFailed::class => ['mail'],
            \NiNaCoder\Updater\Notifications\Notifications\UpdateAvailable::class => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \NiNaCoder\Updater\Notifications\Notifiable::class,

        'mail' => [
            'to' => [
                'address' => env('APP_ADMIN_EMAIL'),
                'name' => env('APP_NAME', 'MusicEngine'),
            ],

            'from' => [
                'address' => 'noreply@ninacoder.info',
                'name' => 'MusicEngine Update',
            ],
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Register custom artisan commands
    |---------------------------------------------------------------------------
    */

    'artisan_commands' => [
        'pre_update' => [
            //'command:signature' => [
            //    'class' => Command class
            //    'params' => []
            //]
        ],
        'post_update' => [

        ],
    ],

];

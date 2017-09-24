<?php

return [


    'default' => env('SELF_UPDATER_SOURCE','github'),

    'version_installed' => env('SELF_UPDATER_VERSION_INSTALLED',''),

    'repository_types' => [
        'github' => [
            'type' => 'github',
            'repository_vendor' => env('SELF_UPDATER_REPO_VENDOR', ''),
            'repository_name' => env('SELF_UPDATER_REPO_NAME', ''),
            'repository_url' => '',
            'download_path' => env('SELF_UPDATER_DOWNLOAD_PATH', '/tmp'),
        ],
    ],

    'exclude_folders' => [
        'node_modules',
        'bootstrap/cache',
        'bower',
        'storage/app',
        'storage/framework',
        'storage/logs',
        'storage/self-update',
        'vendor',
    ],

    'log_events' => env('SELF_UPDATER_LOG_EVENTS', false),

    'mail_to' => [
        'address' => env('SELF_UPDATER_MAILTO_ADDRESS', ''),
        'name' => env('SELF_UPDATER_MAILTO_NAME', ''),
        'subject_update_available' => env('SELF_UPDATER_MAILTO_UPDATE_AVAILABLE_SUBJECT', 'Update available'),
        'subject_update_succeeded' => env('SELF_UPDATER_MAILTO_UPDATE_SUCCEEDED_SUBJECT', 'Update succeeded'),
    ],

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
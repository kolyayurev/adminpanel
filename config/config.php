<?php

return [
    'prefix' => env('AP_PREFIX', 'admin'),

    'name' => env('AP_NAME', 'AP'),

    'redirects' => [
        'dashboard' => false // false or '/url'
    ],
    /*
     |--------------------------------------------------------------------------
     | Interface
     |--------------------------------------------------------------------------
     |
     */

    // bootstrap theme mode ['light','dark']
    'theme' => env('AP_THEME', 'light'),

    'navbar' => [
        'logo' => env('AP_NAME', 'AP'),
        'url' => '/' . env('AP_PREFIX', 'admin'),
    ],

    'breadcrumbs' => [
        'show_dashboard' => true,
    ],
    /*
     |--------------------------------------------------------------------------
     | Permissions and gates (временно)
     |--------------------------------------------------------------------------
     |
    */
    'gates' => [
        'view_tools' => env('AP_GATES_VIEW_TOOLS', false),
        'view_dev' => env('AP_GATES_VIEW_DEV', false),
        'view_media' => env('AP_GATES_VIEW_MEDIA', true),
    ],
    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role' => 'user',
        'redirect' => '/' . env('AP_PREFIX', 'admin'),
    ],

    /*
   |--------------------------------------------------------------------------
   | Multilingual configuration
   |--------------------------------------------------------------------------
   |
   | Here you can specify if you want adminpanel to ship with support for
   | multilingual and what locales are enabled.
   |
   */

    'multilingual' => [
        /*
         * Set whether or not the multilingual is supported by the BREAD input.
         */
        'enabled' => false,

        /*
         * Select default language
         */
        'default' => 'ru',

        /*
         * Select languages that are supported.
         */
        'locales' => [
            'ru',
            'en',
        ],
    ],

    'ymaps' => [
        'key'    => env('AP_YMAPS_KEY', ''),
        'center' => [
            'lat' => env('AP_YMAPS_DEFAULT_CENTER_LAT', '59.93499'),
            'lng' => env('AP_YMAPS_DEFAULT_CENTER_LNG', '30.31907'),
        ],
        'zoom' => env('AP_YMAPS_DEFAULT_ZOOM', 8),
    ],


    // Here you can specify additional assets you would like to be included in the master.blade
    'additional_css' => [
        //'css/custom.css',
    ],

    'additional_js' => [
        //'js/custom.js',
    ],

    'icons' => [
        'bi' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css'
    ],

    // front admin controls panel
    'controls_panel' => [
        'enable' => true,
        'position' => 'top-right', // ['top-left','bottom-left','top-right','bottom-right']
    ],

    'settings' => [
        'cache' => false,
    ],

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],

    'media' => [
        // The allowed mimetypes to be uploaded through the media-manager.
        'allowed_mimetypes' => '*', //All types can be uploaded
        /*
        'allowed_mimetypes' => [
          'image/jpeg',
          'image/png',
          'image/gif',
          'image/bmp',
          'video/mp4',
        ],
        */
        //Path for media-manager. Relative to the filesystem.
        'path' => '/',
        'show_folders' => true,
        'allow_upload' => true,
        'allow_move' => true,
        'allow_delete' => true,
        'allow_create_folder' => true,
        'allow_rename' => true,

        'default_thumb_name' => 'thumb'
    ],


];

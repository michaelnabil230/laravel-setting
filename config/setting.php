<?php

use MichaelNabil230\LaravelSetting\Models\Setting;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Default Settings Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default settings store that gets used while
    | using this settings library.
    |
    | Supported: "json", "database"
    |
    */
    'default' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Drivers Stores
    |--------------------------------------------------------------------------
    |
    | The settings are stored.
    |
    */

    'drivers' => [
        'database' => [
            'driver' => MichaelNabil230\LaravelSetting\Stores\DatabaseSettingStore::class,
            'options' => [
                'model' => Setting::class,
                'table' => 'settings', // name of tabele in dataBase
                'keyColumn' => 'key', // the key of key
                'valueColumn' => 'value', // the key of value
                'cache' => [
                    'enableCache' => false,
                    'cacheTtl' => 15, // TTL in seconds.
                ]
            ],
        ],

        'json' => [
            'driver' => MichaelNabil230\LaravelSetting\Stores\JsonSettingStore::class,
            'options' => [
                'path' => storage_path('settings.json'),
            ]
        ],
    ],

    // Your keys
    'keys' => [
        // 
    ],
];

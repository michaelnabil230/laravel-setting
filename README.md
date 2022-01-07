# This package allows you to persists setting for Laravel projects.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/michaelnabil230/laravel-setting.svg)](https://packagist.org/packages/michaelnabil230/laravel-setting)
[![Total Downloads](https://img.shields.io/packagist/dt/michaelnabil230/laravel-setting.svg)](https://packagist.org/packages/michaelnabil230/laravel-setting)

[![Stars](https://img.shields.io/github/stars/michaelnabil230/laravel-setting)](https://github.com/michaelnabil230/laravel-setting/stargazers)
[![License](https://img.shields.io/github/license/michaelnabil230/laravel-setting)](https://github.com/michaelnabil230/laravel-setting/blob/main/LICENSE)
[![Issues](https://img.shields.io/github/issues/michaelnabil230/laravel-setting)](https://github.com/michaelnabil230/laravel-setting/issues)

## Installation

You can install the package via composer:

```bash
composer require michaelnabil230/laravel-setting
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="setting-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="setting-config"
```

This is the contents of the published config file:

```php
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
];

```

You can either access the setting store via its facade
```php
<?php

use MichaelNabil230\LaravelSetting\Facades\Setting;

Setting::set('foo', 'bar')->save();
Setting::get('foo', 'default value');
Setting::get('nested.element');
Setting::has('foo');
Setting::forget('foo');
Setting::forgetAll();

$settings = Setting::all();

?>
```

You could also use the `setting()` helper:

```php
// Get the store instance
setting();

// Get values
setting('foo');
setting('foo.bar');
setting('foo', 'default value');
setting()->get('foo');
setting()->get('foo.bar');

// Set values
setting(['foo' => 'bar'])->save();
setting(['foo.bar' => 'baz'])->save();
setting()->set('foo', 'bar')->save();

// Method chaining
setting(['foo' => 'bar'])->save();
```

You could also use the @setting() directive blade:

```
@setting('foo')
@setting('foo', 'default value')
```

### Command line helper

```
php artisan setting:forget foo
php artisan setting:get || php artisan setting:get foo
php artisan setting:set-or-update foo bar
```

### Store cache

When reading from the store, you can enable the cache.

You can also configure flushing of the cache when writing and configure time to live.

Reading will come from the store, and then from the cache, this can reduce load on the store.

```php
'cache' => [
  'enableCache' => false,
  'cacheTtl' => 15, // TTL in seconds.
]
```

# Configuration

### Default:

```php
<?php

// config/setting.php

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

    // ...
];
```

You can specify here your default store driver that you would to use.

### Drivers:

```php
<?php

// config/setting.php

return [
    //...
    
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
                'table' => 'settings', // name of table in dataBase
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
];
```

This is the list of the supported store drivers. You can expand this list by adding a custom store driver.

The store config is structured like this:

```php
<?php 

// ...
'custom' => [
    'driver'  => App\Stores\CustomStore::class,
    
    'options' => [
        // ...
    ],
],
```

##### 1. Create the Custom Store class

```php
<?php 

namespace App\Settings;

use MichaelNabil230\LaravelSetting\Contracts\Store;

class CustomStore implements Store
{
    // Implement the contract's methods here
} 
```

##### 2. Register the Custom Store

Go to the `config/setting.php` config file and edit the `drivers` list:

```php
return [
    'drivers' => [
        'custom' => [
            'driver'  => App\Settings\CustomStore::class,
        ],
    ],
];
```

If you used the abstract `MichaelNabil230\LaravelSetting\Contracts\Store` class, you can pass a `options` array
like credential keys, path ...

```php
return [
    'drivers' => [
        'custom' => [
            'driver'  => App\Settings\CustomStore::class,
            'options' => [
                // more customize
            ],
        ],
    ],
];
``` 

Last and not least, you can set it as the `default` store.


# Integration with `stancl/tenancy`

Install the package like usual, but publish the migrations and move them to `migrations/tenant`:

```
php artisan vendor:publish --tag="setting-migrations"
mv database/migrations/*_create_settings_table.php database/migrations/tenant
```

Then add this to your `AppServiceProvider::boot()` method:

```php
Event::listen(TenancyBootstrapped::class, function (TenancyBootstrapped $event) {
    \MichaelNabil230\LaravelSetting\Stores\DatabaseSettingStore::$cacheKey = 'setting.cache.tenant.' . $event->tenancy->tenant->id;
});
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Michael Nabil](https://github.com/michaelnabil230)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

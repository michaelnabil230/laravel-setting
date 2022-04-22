<?php

/**
 *
 * @method get($key, $default = null)
 * @method set($key, $value = null)
 * @method save()
 * @method has($key)
 * @method all()
 * @method flip()
 * @method enable()
 * @method disable()
 * @method forget($key)
 * @method forgetAll()
 *
 * @see \MichaelNabil230\LaravelSetting\LaravelSettingManager
 */

namespace MichaelNabil230\LaravelSetting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-setting';
    }
}

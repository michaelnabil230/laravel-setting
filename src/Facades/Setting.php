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
 * @see \MichaelNabil230\Setting\SettingManager
 */

namespace MichaelNabil230\Setting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  setting
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'setting';
    }
}

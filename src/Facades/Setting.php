<?php

namespace MichaelNabil230\Setting\Facades;

use Illuminate\Support\Facades\Facade;

/**
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
 * @method setExtraColumns($columns)
 *
 * @see \MichaelNabil230\Setting\Stores\SettingStore
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MichaelNabil230\Setting\SettingManager::class;
    }
}

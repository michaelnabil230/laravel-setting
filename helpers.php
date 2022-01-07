<?php

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */

if (!function_exists('setting')) {
    /**
     * Get the setting manager instance.
     *
     * @param string|array|null $key
     * @param string|null $default
     *
     * @return \MichaelNabil230\LaravelSetting\LaravelSettingManager
     */
    function setting($key = null, $default = null)
    {
        $setting = app('laravel-setting');

        if (is_array($key)) {
            $setting->set($key);
        } elseif (!is_null($key)) {
            return $setting->get($key, $default);
        }

        return $setting;
    }
}

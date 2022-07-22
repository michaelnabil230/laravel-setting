<?php

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  setting
 */

if (!function_exists('setting')) {
    /**
     * Get the setting manager instance.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return \MichaelNabil230\Setting\Stores\AbstractStore
     */
    function setting($key = null, $default = null)
    {
        /** @var \MichaelNabil230\Setting\Stores\AbstractStore $setting */
        $setting = app('setting');

        if (is_array($key)) {
            $setting->set($key);
        } elseif (!is_null($key)) {
            return $setting->get($key, $default);
        }

        return $setting;
    }
}

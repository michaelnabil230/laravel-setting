<?php

if (! function_exists('setting')) {
    /**
     * Get the setting manager instance.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \MichaelNabil230\Setting\Stores\AbstractStore
     */
    function setting($key = null, $default = null)
    {
        /** @var \MichaelNabil230\Setting\SettingManager $instants */
        $instants = app('setting');

        if (is_null($key)) {
            return $instants;
        }

        if (is_array($key)) {
            return $instants->set($key);
        }

        return $instants->get($key, $default);
    }
}

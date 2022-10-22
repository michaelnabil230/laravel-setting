<?php

if (! function_exists('setting')) {
    /**
     * Get the setting manager instance.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \MichaelNabil230\Setting\Stores\SettingStore
     */
    function setting($key = null, $default = null)
    {
        /** @var \MichaelNabil230\Setting\Stores\SettingStore $instants */
        $instants = app(\MichaelNabil230\Setting\SettingManager::class);

        if (is_array($key)) {
            $instants->set($key);
        } elseif (! is_null($key)) {
            return $instants->get($key, $default);
        }

        return $instants;
    }
}

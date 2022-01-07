<?php

namespace MichaelNabil230\LaravelSetting\Contracts;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
interface Store
{
    /**
     * Get a specific key from the settings data.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Check loaded the data from the store.
     *
     * @return void
     */
    public function checkLoaded(): void;

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value = null);

    /**
     * Determine if a key exists in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Save any changes done to the settings data.
     *
     * @return array
     */
    public function save();

    /**
     * Write the data into the store.
     *
     * @param array $data
     *
     * @return void
     */
    public function write(array $data);

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all();

    /**
     * Unset a key in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key);

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    public function forgetAll();
}

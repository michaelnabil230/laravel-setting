<?php

namespace MichaelNabil230\Setting\Interfaces;

interface Store
{
    /**
     * Get a specific key from the settings data.
     *
     * @param  string  $key
     * @param  string  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Loaded data from the store.
     *
     * @return void
     */
    public function loadedData(): void;

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param  string|array  $key
     * @param  mixed  $value
     * @return $this
     */
    public function set($key, $value = null);

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string  $key
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
     * @param  array  $data
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
     * Flips a boolean to its opposite value.
     *
     * This method exists for convenience.
     *
     * @param  mixed  $key
     * @return $this
     */
    public function flip($key);

    /**
     * Sets the specified key to true.
     *
     * @param  mixed  $key
     * @return $this
     */
    public function enable($key);

    /**
     * Sets the specified key to false.
     *
     * @param  mixed  $key
     * @return $this
     */
    public function disable($key);

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
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

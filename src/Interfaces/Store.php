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
     * @return bool
     */
    public function has(string $key);

    /**
     * Save any changes done to the settings data.
     *
     * @return array
     */
    public function save();

    /**
     * Write the data into the store.
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
     * Flips a boolean to its opposite value.
     *
     * This method exists for convenience.
     *
     * @return $this
     */
    public function flip(mixed $key);

    /**
     * Sets the specified key to true.
     *
     * @return $this
     */
    public function enable(mixed $key);

    /**
     * Sets the specified key to false.
     *
     * @return $this
     */
    public function disable(mixed $key);

    /**
     * Unset a key in the settings data.
     *
     * @return bool
     */
    public function forget(string $key);

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    public function forgetAll();

    /**
     * Set extra columns to be added to the rows.
     *
     * @return $this
     */
    public function setExtraColumns(array $columns);
}

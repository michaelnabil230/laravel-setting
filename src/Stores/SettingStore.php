<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use MichaelNabil230\Setting\Interfaces\Store;

abstract class SettingStore implements Store
{
    /**
     * The laravel application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * SettingStore constructor.
     */
    public function __construct(Application $app, array $options = [])
    {
        $this->app = $app;
        $this->postOptions($options);
    }

    /**
     * Fire the post options to customize the store.
     */
    abstract protected function postOptions(array $options): void;

    /**
     * Get a specific key from the settings data.
     *
     * @param  string|int|null  $key
     * @param  mixed  $default
     */
    public function get($key, $default = null): mixed
    {
        $this->loadedData();

        $default = $default ?? config('setting.defaults.'.$key);

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Loaded data from the store.
     */
    abstract public function loadedData(): void;

    /**
     * Set a specific key to a value in the settings data.
     *
     * @return $this
     */
    public function set(mixed $key, mixed $value = null): self
    {
        $this->loadedData();

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Arr::set($this->data, $k, $v);
            }
        } else {
            Arr::set($this->data, $key, $value);
        }

        return $this;
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string|array  $key
     */
    public function has($key): bool
    {
        $this->loadedData();

        return Arr::has($this->data, $key);
    }

    /**
     * Save any changes done to the settings data.
     */
    public function save(): array
    {
        $this->write($this->data);

        return $this->data;
    }

    /**
     * Write the data into the store.
     */
    abstract public function write(array $data): void;

    /**
     * Get all settings data.
     */
    public function all(): array
    {
        $this->loadedData();

        return $this->data;
    }

    /**
     * Flips a boolean to its opposite value.
     *
     * This method exists for convenience.
     *
     * @return $this
     */
    public function flip(mixed $key): self
    {
        return $this->set($key, ! $this->get($key));
    }

    /**
     * Sets the specified key to true.
     *
     * @return $this
     */
    public function enable(mixed $key): self
    {
        return $this->set($key, true);
    }

    /**
     * Sets the specified key to false.
     *
     * @return $this
     */
    public function disable(mixed $key): self
    {
        return $this->set($key, false);
    }

    /**
     * Unset a key in the settings data.
     */
    abstract public function forget(string $key): bool;

    /**
     * Unset all keys in the settings data.
     */
    abstract public function forgetAll(): bool;

    /**
     * Set extra columns to be added to the rows.
     *
     * @return $this
     */
    public function setExtraColumns(array $columns)
    {
        return $this;
    }
}

<?php

namespace MichaelNabil230\LaravelSetting\Stores;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use MichaelNabil230\LaravelSetting\Interfaces\Store;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
abstract class AbstractStore implements Store
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
     * AbstractStore constructor.
     *
     * @param  Application $app
     * @param  array $options
     */
    public function __construct(Application $app, array $options = [])
    {
        $this->app = $app;
        $this->postOptions($options);
    }

    /**
     * Fire the post options to customize the store.
     *
     * @param  array  $options
     *
     * @return void
     */
    abstract protected function postOptions(array $options): void;

    /**
     * Get a specific key from the settings data.
     *
     * @param string|int|null $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null): mixed
    {
        $this->loadedData();

        $default = $default ?? config('setting.defaults.' . $key);

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Loaded data from the store.
     *
     * @return void
     */
    abstract public function loadedData(): void;

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value = null): self
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
     * @param string|array $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        $this->loadedData();

        return Arr::has($this->data, $key);
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return array
     */
    public function save(): array
    {
        $this->write($this->data);

        return $this->data;
    }

    /**
     * Write the data into the store.
     *
     * @param array $data
     *
     * @return void
     */
    abstract public function write(array $data): void;

    /**
     * Get all settings data.
     *
     * @return array
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
     * @param mixed $key
     *
     * @return $this
     */
    public function flip($key): self
    {
        return $this->set($key, ! $this->get($key));
    }

    /**
     * Sets the specified key to true.
     *
     * @param mixed $key
     *
     * @return $this
     */
    public function enable($key): self
    {
        return $this->set($key, true);
    }

    /**
     * Sets the specified key to false.
     *
     * @param mixed $key
     *
     * @return $this
     */
    public function disable($key): self
    {
        return $this->set($key, false);
    }

    /**
     * Unset a key in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    abstract public function forget($key): bool;

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    abstract public function forgetAll(): bool;
}

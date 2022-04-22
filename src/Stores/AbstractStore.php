<?php

namespace MichaelNabil230\LaravelSetting\Stores;

use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use MichaelNabil230\LaravelSetting\Contracts\Store;

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
     */
    abstract protected function postOptions(array $options);

    /**
     * Get a specific key from the settings data.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    public function get($key, $default = null): mixed
    {
        $this->checkLoaded();

        return Arr::get($this->data, $key, $default);
    }

    /**
     * Check loaded the data from the store.
     *
     * @return void
     */
    abstract public function checkLoaded(): void;

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value = null)
    {
        $this->checkLoaded();

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
     * @param string $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        $this->checkLoaded();

        return Arr::has($this->data, $key);
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return array
     */
    public function save()
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
    abstract public function write(array $data);

    /**
     * Get all settings data.
     *
     * @return array
     */
    public function all(): array
    {
        $this->checkLoaded();

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
    public function flip($key)
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
    public function enable($key)
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
    public function disable($key)
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
    abstract public function forget($key);

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    abstract public function forgetAll();
}

<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  setting
 */
class RedisSettingStore extends AbstractStore
{
    /**
     * The redis manager.
     *
     * @var RedisManager
     */
    protected $manager;

    /**
     * @param array $options
     *
     * @return void
     */
    protected function postOptions(array $options): void
    {
        $this->manager = new RedisManager(
            $this->app,
            Arr::pull($options, 'client', 'predis'),
            $options
        );
    }

    /**
     * Loaded data from the store.
     *
     * @return void
     */
    public function loadedData(): void
    {
        $data = $this->command('get', ['settings']);

        $this->data = is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     *
     * @param array $data
     *
     * @return void
     */
    public function write(array $data): void
    {
        $this->command('set', ['settings', json_encode($data)]);
    }

    /**
     * Unset a key in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key): bool
    {
        $this->loadedData();

        if (!Arr::has($this->data, $key)) {
            return false;
        }

        Arr::forget($this->data, $key);

        $this->write($this->data);

        return true;
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    public function forgetAll(): bool
    {
        $this->write([]);

        return true;
    }

    /**
     * Get a Redis connection by name.
     *
     * @param  string|null  $name
     *
     * @return \Illuminate\Redis\Connections\Connection
     */
    private function connection($name = null)
    {
        return $this->manager->connection($name);
    }

    /**
     * Run a command against the Redis database.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    private function command(string $method, array $parameters = [])
    {
        return $this->connection()->command($method, $parameters);
    }
}

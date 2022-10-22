<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Support\Arr;

class RedisSettingStore extends SettingStore
{
    /**
     * The Redis instance.
     *
     * @var \Illuminate\Contracts\Redis\Factory
     */
    protected $redis;

    /**
     * The Redis key prefix.
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * The Redis connection to use for broadcasting.
     *
     * @var ?string
     */
    protected $connection = null;

    /**
     * @param  array  $options
     * @return void
     */
    protected function postOptions(array $options): void
    {
        $this->redis = $this->app->make('redis');
        $this->prefix = Arr::get($options, 'prefix', '');
        $this->connection = Arr::get($options, 'connection');
    }

    /**
     * Loaded data from the store.
     *
     * @return void
     */
    public function loadedData(): void
    {
        $data = $this->command('get', [$this->prefix.'settings']);

        $this->data = is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     * @return void
     */
    public function write(array $data): void
    {
        $this->command('set', [$this->prefix.'settings', json_encode($data)]);
    }

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key): bool
    {
        $this->loadedData();

        if (! Arr::has($this->data, $key)) {
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
     * @return \Illuminate\Redis\Connections\Connection
     */
    private function connection()
    {
        return $this->redis->connection($this->connection);
    }

    /**
     * Run a command against the Redis database.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    private function command(string $method, array $parameters = [])
    {
        return $this->connection()->command($method, $parameters);
    }
}

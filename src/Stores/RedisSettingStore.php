<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Redis\Connections\Connection;
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

    protected function postOptions(array $options): void
    {
        $this->redis = $this->app->make('redis');
        $this->prefix = Arr::get($options, 'prefix', '');
        $this->connection = Arr::get($options, 'connection');
    }

    /**
     * Loaded data from the store.
     */
    public function loadedData(): void
    {
        $data = $this->command('get', [$this->prefix.'settings']);

        $this->data = is_string($data) ? json_decode($data, true) : [];
    }

    /**
     * Write the data into the store.
     */
    public function write(array $data): void
    {
        $this->command('set', [$this->prefix.'settings', json_encode($data)]);
    }

    /**
     * Unset a key in the settings data.
     */
    public function forget(string $key): bool
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
     */
    public function forgetAll(): bool
    {
        $this->write([]);

        return true;
    }

    /**
     * Get a Redis connection by name.
     */
    private function connection(): Connection
    {
        return $this->redis->connection($this->connection);
    }

    /**
     * Run a command against the Redis database.
     */
    private function command(string $method, array $parameters = []): mixed
    {
        return $this->connection()->command($method, $parameters);
    }
}

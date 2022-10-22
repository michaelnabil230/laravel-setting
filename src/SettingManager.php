<?php

namespace MichaelNabil230\Setting;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;

class SettingManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('setting.default', 'json');
    }

    /**
     * Register a new store.
     *
     * @param  string  $driver
     * @param  array  $params
     * @return self
     */
    public function register(string $driver, array $params)
    {
        return $this->extend($driver, function () use ($params) {
            return $this->container->make($params['driver'], [
                'options' => Arr::get($params, 'options', []),
            ]);
        });
    }
}

<?php

namespace MichaelNabil230\LaravelSetting;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class LaravelSettingManager extends Manager
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
     * @param string $driver
     * @param array $params
     *
     * @return $this
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

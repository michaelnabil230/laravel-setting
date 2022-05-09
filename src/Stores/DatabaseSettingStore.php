<?php

namespace MichaelNabil230\LaravelSetting\Stores;

use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class DatabaseSettingStore extends AbstractStore
{
    /** @var string */
    public static $cacheKey = 'setting.cache';

    /**
     * The settings originalData.
     *
     * @var array
     */
    protected $originalData = [];

    /**
     * The eloquent model.
     *
     * @var Model
     */
    protected $model;

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extraColumns = [];

    /**
     * The cache manager instance.
     *
     * @var CacheManager
     */
    protected $cache;

    /**
     * Cache TTL in seconds.
     *
     * @var int
     */
    protected $cacheTtl;

    /**
     * Whether to reset the cache when changing a setting.
     *
     * @var bool
     */
    protected $enableCache;

    /**
     * SettingStore constructor.
     *
     * @param array $options
     *
     * @return void
     */
    public function postOptions($options = [])
    {
        $this->model = $options['model'];
        $this->cache = $this->app['cache'];
        $this->enableCache = $options['cache']['enableCache'] ?: true;
        $this->cacheTtl = $options['cache']['cacheTtl'] ?: 15;
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
        if ($this->enableCache) {
            $this->cache->store()->forget(self::$cacheKey);
        }

        $changes = $this->getChanges($data);

        $data = $this->prepareData(Arr::dot($changes['updated'] + $changes['inserted']));

        $this->model::upsert($data, ['key', 'value']);

        $this->checkLoaded();
    }

    /**
     * Get the changed settings data.
     *
     * @param array $data
     *
     * @return array
     */
    private function getChanges(array $data): array
    {
        $changes = [
            'inserted' => Arr::undot($data),
            'updated' => [],
        ];

        foreach ($this->originalData as $key => $value) {
            if (Arr::has($changes['inserted'], $key) && $value != $changes['inserted'][$key]) {
                $changes['updated'][$key] = $changes['inserted'][$key];
            }
            Arr::forget($changes['inserted'], $key);
        }

        return $changes;
    }

    /**
     * Transforms settings data into an array ready to be inserted into the database.
     * Call array_dot on a multidimensional array before passing it into this method!
     *
     * @param array $data
     *
     * @return array
     */
    private function prepareData(array $data): array
    {
        $dbData = [];
        $extraColumns = $this->extraColumns ? $this->extraColumns : [];

        foreach ($data as $key => $value) {
            $dbData[] = array_merge($extraColumns, [
                'key' => $key,
                'value' => $value,
            ]);
        }

        return $dbData;
    }

    /**
     * Check if the settings data has been loaded.
     *
     * @return void
     */
    public function checkLoaded(): void
    {
        if ($this->enableCache) {
            $data = $this->cache->store()->remember(self::$cacheKey, $this->cacheTtl, function () {
                return $this->readOriginalData();
            });
        } else {
            $data = $this->readOriginalData();
        }

        $this->data = Arr::undot($data);
    }

    /**
     * Read the original data from dataBase.
     *
     * @return array
     */
    private function readOriginalData(): array
    {
        return $this->model::pluck('value', 'key')->toArray();
    }

    /**
     * Unset a key in the settings data.
     *
     * @param string $key
     *
     * @return bool
     */
    public function forget($key)
    {
        $this->checkLoaded();

        if (Arr::has($this->data, $key)) {
            $data[$key] = Arr::get($this->data, $key);

            $deleted = array_keys(Arr::dot($data));

            $this->syncDeleted($deleted);

            $this->cache->store()->forget(self::$cacheKey);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Sync the deleted records.
     *
     * @param array $deleted
     */
    private function syncDeleted(array $deleted): void
    {
        if (! empty($deleted)) {
            $this->model::whereIn('key', $deleted)->delete();
        }
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    public function forgetAll()
    {
        $this->model::truncate();

        $this->cache->store()->forget(self::$cacheKey);

        return true;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function setExtraColumns(array $columns)
    {
        $this->extraColumns = $columns;

        return $this;
    }
}

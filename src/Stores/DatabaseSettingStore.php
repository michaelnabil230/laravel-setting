<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class DatabaseSettingStore extends SettingStore
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
     */
    public function postOptions(array $options = []): void
    {
        $this->model = $options['model'];
        $this->cache = $this->app['cache'];
        $this->enableCache = $options['cache']['enableCache'] ?: true;
        $this->cacheTtl = $options['cache']['cacheTtl'] ?: 15;
    }

    /**
     * Write the data into the store.
     */
    public function write(array $data): void
    {
        if ($this->enableCache) {
            $this->cache->forget(self::$cacheKey);
        }

        $changes = $this->getChanges($data);

        $data = $this->prepareData(Arr::dot($changes['updated'] + $changes['inserted']));

        $this->model::upsert($data, ['key', 'value']);

        $this->loadedData();
    }

    /**
     * Loaded data from the store.
     */
    public function loadedData(): void
    {
        if ($this->enableCache) {
            $data = $this->cache->remember(self::$cacheKey, $this->cacheTtl, function () {
                return $this->readOriginalData();
            });
        } else {
            $data = $this->readOriginalData();
        }

        $this->data = Arr::undot($data);
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

        $data[$key] = Arr::get($this->data, $key);

        $deleted = array_keys(Arr::dot($data));

        $this->syncDeleted($deleted);

        $this->cache->forget(self::$cacheKey);

        return true;
    }

    /**
     * Unset all keys in the settings data.
     */
    public function forgetAll(): bool
    {
        $this->model::truncate();

        $this->cache->forget(self::$cacheKey);

        return true;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @return $this
     */
    public function setExtraColumns(array $columns): self
    {
        $this->extraColumns = $columns;

        return $this;
    }

    /**
     * Sync the deleted records.
     */
    private function syncDeleted(array $deleted): void
    {
        if (! empty($deleted)) {
            $this->model::whereIn('key', $deleted)->delete();
        }
    }

    /**
     * Read the original data from dataBase.
     */
    private function readOriginalData(): array
    {
        return $this->model::pluck('value', 'key')->toArray();
    }

    /**
     * Get the changed settings data.
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
}

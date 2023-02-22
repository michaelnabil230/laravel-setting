<?php

namespace MichaelNabil230\Setting\Stores;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use RuntimeException;

class JsonSettingStore extends SettingStore
{
    /**
     * The path.
     *
     * @var string
     */
    protected $path;

    /**
     * The files.
     *
     * @var Filesystem
     */
    protected $files = null;

    public function postOptions(array $options = []): void
    {
        $this->path = $options['path'];
        $this->files = $this->app['files'];

        $this->throwAnyException();
    }

    /**
     * Loaded data from the store.
     *
     *
     * @throws RuntimeException
     */
    public function loadedData(): void
    {
        $contents = $this->files->get($this->path);
        $data = json_decode($contents, true);

        if (is_null($data)) {
            throw new RuntimeException("Invalid JSON file in [{$this->path}]");
        }

        $this->data = Arr::undot($data);
    }

    /**
     * Write the data into the store.
     */
    public function write(array $data): void
    {
        $contents = $data ? json_encode($data) : '{}';

        $this->files->put($this->path, $contents);
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

        $this->files->put($this->path, $this->data);

        return true;
    }

    /**
     * Unset all keys in the settings data.
     */
    public function forgetAll(): bool
    {
        $contents = '{}';

        $this->files->put($this->path, $contents);

        return true;
    }

    /**
     * Throw any Exception first.
     *
     *
     * @throws InvalidArgumentException
     */
    private function throwAnyException(): void
    {
        $path = $this->path;
        $files = $this->files;

        // If the file does not already exist, we will attempt to create it.
        if (! $files->exists($path)) {
            $result = $files->put($path, '{}');
            if ($result === false) {
                throw new InvalidArgumentException("Could not write to $path.");
            }
        }

        if (! $files->isWritable($path)) {
            throw new InvalidArgumentException("$path is not writable.");
        }
    }
}

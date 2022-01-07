<?php

namespace MichaelNabil230\LaravelSetting\Stores;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use RuntimeException;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class JsonSettingStore extends AbstractStore
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

    /**
     * @param array $options
     *
     * @return void
     */
    public function postOptions($options = [])
    {
        $this->path = $options['path'];
        $this->files = $this->app['files'];

        $this->throwAnyException();
    }

    /**
     * Throw any Exception first.
     *
     * @return void
     */
    private function throwAnyException()
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
            Arr::forget($this->data, $key);

            $contents = $this->data ? json_encode($this->data) : '{}';

            $this->files->put($this->path, $contents);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Read the data from the store.
     *
     * @return void
     */
    public function checkLoaded(): void
    {
        $contents = $this->files->get($this->path);
        $data = json_decode($contents, true);

        if (is_null($data)) {
            throw new RuntimeException("Invalid JSON file in [{$this->path}]");
        }

        $this->data = Arr::undot($data);
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return bool
     */
    public function forgetAll()
    {
        $contents = '{}';

        $this->files->put($this->path, $contents);

        return true;
    }

    /**
     * Write the data into the store.
     *
     * @param array $data
     */
    public function write(array $data)
    {
        $contents = $data ? json_encode($data) : '{}';

        $this->files->put($this->path, $contents);
    }
}

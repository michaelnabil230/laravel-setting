<?php

namespace MichaelNabil230\LaravelSetting\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MichaelNabil230\LaravelSetting\LaravelSettingServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MichaelNabil230\\LaravelSetting\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelSettingServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__ . '/../database/migrations/create_settings_table.php.stub';
        $migration->up();
    }
}

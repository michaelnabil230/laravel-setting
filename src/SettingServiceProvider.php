<?php

namespace MichaelNabil230\Setting;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Blade;
use MichaelNabil230\Setting\Commands\ForgetSetting;
use MichaelNabil230\Setting\Commands\GetSetting;
use MichaelNabil230\Setting\Commands\SetOrUpdateSetting;
use MichaelNabil230\Setting\Stores\SettingStore;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SettingServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('setting')
            ->hasConfigFile()
            ->hasMigration('create_settings_table')
            ->hasCommands([
                GetSetting::class,
                ForgetSetting::class,
                SetOrUpdateSetting::class,
            ]);
    }

    public function registeringPackage()
    {
        $this->app->singleton(SettingManager::class);

        $this->app->extend(SettingManager::class, function (SettingManager $manager, $app) {
            foreach ($app['config']->get('setting.drivers', []) as $driver => $params) {
                $manager->register($driver, $params);
            }

            return $manager;
        });

        $this->app->singleton(SettingStore::class, function ($app) {
            return $app[SettingManager::class]->driver();
        });
    }

    public function packageBooted(): void
    {
        Blade::directive('setting', function ($key, $default = null) {
            return "<?php echo setting($key, $default); ?>";
        });
    }

    public function provides(): array
    {
        return [
            SettingManager::class,
            SettingStore::class,
        ];
    }
}

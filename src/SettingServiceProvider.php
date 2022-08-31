<?php

namespace MichaelNabil230\Setting;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Blade;
use MichaelNabil230\Setting\Commands\ForgetSetting;
use MichaelNabil230\Setting\Commands\GetSetting;
use MichaelNabil230\Setting\Commands\SetOrUpdateSetting;
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

    public function packageRegistered()
    {
        $this->app->singleton('setting', function ($app) {
            return new SettingManager($app);
        });

        $this->app->extend('setting', function (SettingManager $manager, $app) {
            foreach ($app['config']->get('setting.drivers', []) as $driver => $params) {
                $manager->register($driver, $params);
            }

            return $manager;
        });

        $this->app->singleton('setting.driver', function ($app) {
            return $app['setting']->driver();
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
            'setting',
            'setting.driver',
        ];
    }
}

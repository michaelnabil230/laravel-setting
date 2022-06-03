<?php

namespace MichaelNabil230\LaravelSetting;

use Illuminate\Support\Facades\Blade;
use MichaelNabil230\LaravelSetting\Commands\ForgetSetting;
use MichaelNabil230\LaravelSetting\Commands\GetSetting;
use MichaelNabil230\LaravelSetting\Commands\SetOrUpdateSetting;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class LaravelSettingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-setting')
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
        $this->app->extend(LaravelSettingManager::class, function (LaravelSettingManager $manager, $app) {
            foreach ($app['config']->get('setting.drivers', []) as $driver => $params) {
                $manager->register($driver, $params);
            }

            return $manager;
        });

        $this->app->bind('laravel-setting', function ($app) {
            return $app->make(LaravelSettingManager::class)->driver();
        });
    }

    public function packageBooted(): void
    {
        Blade::directive('setting', function ($key, $default = null) {
            return "<?php echo setting($key, $default); ?>";
        });
    }
}

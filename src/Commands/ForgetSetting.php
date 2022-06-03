<?php

namespace MichaelNabil230\LaravelSetting\Commands;

use Error;
use Illuminate\Console\Command;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  laravel-setting
 */
class ForgetSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:forget {key* : Setting key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forget one or more settings.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->argument('key');

        $setting = setting();

        if ($setting->has($key)) {
            if ($setting->forget($key)) {
                $this->info('Forget one or more settings successfully.');
                return Command::SUCCESS;
            }

            $this->error('Error.');

            return Command::FAILURE;
        }

        $this->error('No setting matches the given key.');
        return Command::FAILURE;
    }
}

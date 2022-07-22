<?php

namespace MichaelNabil230\Setting\Commands;

use Illuminate\Console\Command;

/**
 *
 * @author   Michael Nabil <michaelnabil926@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  setting
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
     * @return int
     */
    public function handle()
    {
        $key = $this->argument('key');

        if (setting()->has($key)) {
            if (setting()->forget($key)) {
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

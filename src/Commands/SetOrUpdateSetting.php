<?php

namespace MichaelNabil230\Setting\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SetOrUpdateSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:set-or-update {key : Setting key} {value : Setting value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set new or update value of an item in the setting.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        $settings = setting()->set($key, $value)->save();

        if (Arr::has($settings, $key)) {
            $this->info('Insert '.$key.' into a new setting successfully.');
        } else {
            $this->info('Updated '.$key.' setting successfully.');
        }

        return Command::SUCCESS;
    }
}

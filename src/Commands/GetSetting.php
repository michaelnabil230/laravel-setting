<?php

namespace MichaelNabil230\Setting\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class GetSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:get {key?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all settings or filter in the key.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = $this->argument('key');

        $settings = setting()->all();

        if ($key) {
            if (Arr::has($settings, $key)) {
                $value = Arr::get($settings, $key);
                $settings = [$key => $value];
            } else {
                $settings = [];
            }
        }

        $settings = $this->formatSetting(Arr::dot($settings));

        return $this->print($settings);
    }

    /**
     * Format settings befor print.
     *
     * @param  array  $settings
     * @return Collection
     */
    private function formatSetting($settings)
    {
        return collect($settings)
            ->map(function ($value, $key) {
                return ['key' => $key, 'value' => $value];
            });
    }

    /**
     * Print the settings in screen.
     *
     * @param  Collection  $settings
     * @return int
     */
    private function print($settings)
    {
        if ($settings->count() > 0) {
            $this->table(
                [
                    'key',
                    'value',
                ],
                $settings
            );

            $this->info('Values found '.$settings->count());

            return Command::SUCCESS;
        }

        $this->error('There is no values');

        return Command::FAILURE;
    }
}

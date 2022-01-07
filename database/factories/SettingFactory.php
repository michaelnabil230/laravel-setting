<?php

namespace MichaelNabil230\LaravelSetting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MichaelNabil230\LaravelSetting\Models\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'key' => 'foo',
            'value' => 'bar',
        ];
    }
}

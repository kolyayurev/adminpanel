<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'display_name' => $this->faker->words(2, true),
            'value' => $this->faker->sentence(),
            'details' => null,
        ];
    }
}

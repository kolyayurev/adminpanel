<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Translation;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'table_name' => 'settings',
            'column_name' => 'value',
            'foreign_key' => $this->faker->numberBetween(1, 1000),
            'locale' => $this->faker->randomElement(['ru', 'en']),
            'value' => $this->faker->sentence(),
        ];
    }
}

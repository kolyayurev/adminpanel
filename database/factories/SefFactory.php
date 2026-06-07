<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Sef;

class SefFactory extends Factory
{
    protected $model = Sef::class;

    public function definition(): array
    {
        return [
            'url' => '/source-'.$this->faker->unique()->slug(),
            'get_params' => ['page' => (string) $this->faker->numberBetween(1, 10)],
            'alias' => '/alias-'.$this->faker->unique()->slug(),
            'status' => 1,
        ];
    }
}

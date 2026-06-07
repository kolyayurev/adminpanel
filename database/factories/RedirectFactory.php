<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Redirect;

class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition(): array
    {
        return [
            'from' => '/old-'.$this->faker->unique()->slug(),
            'get_params' => ['utm' => $this->faker->word()],
            'to' => '/new-'.$this->faker->unique()->slug(),
            'status' => 1,
        ];
    }
}

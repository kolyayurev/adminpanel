<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Seo;

class SeoFactory extends Factory
{
    protected $model = Seo::class;

    public function definition(): array
    {
        return [
            'url' => '/seo-'.$this->faker->unique()->slug(),
            'get_params' => ['ref' => $this->faker->word()],
            'title' => $this->faker->sentence(3),
            'h1' => $this->faker->sentence(3),
            'seo_text' => $this->faker->paragraph(),
            'meta_keywords' => implode(', ', $this->faker->words(3)),
            'meta_description' => $this->faker->sentence(),
            'meta_og_title' => $this->faker->sentence(3),
            'meta_og_description' => $this->faker->sentence(),
            'status' => 1,
        ];
    }
}

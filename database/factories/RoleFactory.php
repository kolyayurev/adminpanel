<?php

namespace KY\AdminPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use KY\AdminPanel\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->slug(2);

        return [
            'name' => $name,
            'display_name' => ucfirst(str_replace('-', ' ', $name)),
            'description' => $this->faker->sentence(),
        ];
    }

    public function admin(): self
    {
        return $this->state([
            'name' => 'admin',
            'display_name' => 'Admin',
        ]);
    }

    public function user(): self
    {
        return $this->state([
            'name' => 'user',
            'display_name' => 'User',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Portal',
            'key' => strtoupper(fake()->unique()->lexify('???')),
            'description' => fake()->sentence(),
            'created_by' => User::factory()->admin(),
            'is_active' => true,
        ];
    }
}

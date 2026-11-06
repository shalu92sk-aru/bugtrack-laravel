<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BugFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'bug_key' => 'BUG-' . Str::upper(Str::random(6)),
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'steps_to_reproduce' => "1. Go to page\n2. Click button",
            'expected_result' => 'Expected behavior',
            'actual_result' => 'Actual broken behavior',
            'environment' => 'Chrome / Windows',
            'severity' => 'medium',
            'priority' => 'normal',
            'status' => 'open',
            'reported_by' => User::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    return [
        'title' => $this->faker->sentence(3),
        'description' => $this->faker->paragraph,
        'status' => $this->faker->randomElement(['active', 'completed']),
        'deadline' => $this->faker->dateTimeBetween('now', '+1 month'),
    ];
}
}

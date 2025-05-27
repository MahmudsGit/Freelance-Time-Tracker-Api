<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    $start = $this->faker->dateTimeBetween('-1 week', 'now');
    $end = (clone $start)->modify('+'.rand(1, 8).' hours');

    return [
        'start_time' => $start,
        'end_time' => $end,
        'description' => $this->faker->sentence,
        'hours' => round(($end->getTimestamp() - $start->getTimestamp()) / 3600, 2),
        'tag' => $this->faker->randomElement(['billable', 'non-billable']),
    ];
}
}

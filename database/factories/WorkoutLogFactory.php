<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkoutLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkoutLog>
 */
class WorkoutLogFactory extends Factory
{
    protected $model = WorkoutLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->client(),
            'coach_id' => null,
            'completed_at' => now()->toDateString(),
            'program_name' => fake()->randomElement(['Strength', 'Hypertrophy', 'Mobility', null]),
            'notes' => fake()->optional()->sentence(),
            'duration_minutes' => fake()->optional()->numberBetween(20, 120),
        ];
    }
}

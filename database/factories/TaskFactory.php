<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id'   => User::factory()->administrator()->create(),
            'assignee_id' => User::factory()->assignee()->create(),
            'name'        => fake()->text(20),
            'status'      => StatusEnum::randomUnique()->value,
            'description' => fake()->realText(),
            'deadline_at' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ];
    }
}

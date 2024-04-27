<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => bcrypt('Apollo17'),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function administrator(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::firstWhere('name', RoleEnum::ADMINISTRATOR->value)->id,
        ]);
    }

    public function assignee(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::firstWhere('name', RoleEnum::ASSIGNEE->value)->id,
        ]);
    }
}

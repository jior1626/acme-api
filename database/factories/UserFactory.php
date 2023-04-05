<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'nit' => fake()->unique()->randomNumber(9, true),
            'firstname'=> fake()->firstName(),
            'lastname' => fake()->lastName(),
            'surnames' => fake()->lastName(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'type' => fake()->randomElement(['owner', 'driver'])
        ];
    }
}

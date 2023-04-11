<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner_id = User::where("type", "owner")->get()->random()->id;
        $driver_id = User::where("type", "driver")->get()->random()->id;
        $driver_id = $driver_id == $owner_id ? User::all()->random()->id : $driver_id;

        return [
            'registration' => fake()->randomNumber(6, true),
            'color' => fake()->colorName(),
            'brand' => fake()->randomElement(['chevrolet', 'kia', 'mazda', 'hyandia', 'toyota', 'bmw', 'renault', 'volkswagens']),
            'type' => fake()->randomElement(['public', 'particular']),
            'owner_id' => $owner_id,
            'driver_id' => $driver_id
        ];
    }
}

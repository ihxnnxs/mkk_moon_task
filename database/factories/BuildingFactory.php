<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'latitude' => fake()->latitude(55.5, 56.0), // Примерно координаты Москвы
            'longitude' => fake()->longitude(37.0, 38.0),
        ];
    }
}

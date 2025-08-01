<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activities = [
            'Торговля',
            'Общественное питание',
            'Услуги',
            'Образование',
            'Медицина',
            'Финансы',
            'IT-услуги',
            'Производство',
            'Строительство',
            'Транспорт',
        ];

        return [
            'parent_id' => $this->faker->boolean(30) ? Activity::factory() : null,
            'name' => $this->faker->randomElement($activities),
        ];
    }

    public function root(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => null,
        ]);
    }
}

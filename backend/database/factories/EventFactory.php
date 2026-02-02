<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'occurrence' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'organizer_id' => User::factory(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}

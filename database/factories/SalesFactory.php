<?php

namespace Database\Factories;

use App\Models\Clients;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sales>
 */
class SalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => $this->faker->randomFloat(2, 1, 100),
            'payment' => $this->faker->randomFloat(2, 1, 100),
            'date' => $this->faker->date(),
            'client_id' => Clients::factory(),
            'user_id' => User::factory(),
        ];
    }
}

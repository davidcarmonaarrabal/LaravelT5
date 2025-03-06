<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clients>
 */
class ClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'identify' => $this->faker->sentence(),
            'telephone' => $this->faker->numerify('#########'),
            'email' => $this->faker->safeEmail,
            'company' => $this->faker->sentence(),
        ];
    }
}

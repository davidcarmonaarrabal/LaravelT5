<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Items>
 */
class ItemsFactory extends Factory
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
            'image' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'qty' => $this->faker->randomNumber(1),
            'date' => $this->faker->date(),
            'product_id' => Products::factory(),
        ];
    }
}

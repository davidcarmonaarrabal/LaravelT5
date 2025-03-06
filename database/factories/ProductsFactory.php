<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->text(100),
            'purchase_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(1, 1000),
            'min_stock' => $this->faker->numberBetween(1, 1000),
            'expiration_date' => $this->faker->date(),
            'active' => $this->faker->boolean(),
            'categories_id' => Categories::factory(),
            'bar_code' => $this->faker->ean13(),
        ];
    }
}

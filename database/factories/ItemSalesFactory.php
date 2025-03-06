<?php

namespace Database\Factories;

use App\Models\Items;
use App\Models\Sales;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemSales>
 */
class ItemSalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'qty' => $this->faker->randomNumber(1),
            'date' => $this->faker->date(),
            'items_id' => Items::factory(),
            'sales_id' => Sales::factory(),
        ];
    }
}

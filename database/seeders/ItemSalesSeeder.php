<?php

namespace Database\Seeders;

use App\Models\ItemSales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemSales::factory(10)->create();
    }
}

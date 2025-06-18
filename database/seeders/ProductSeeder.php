<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\factory as Faker;
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'لبنة',
            'description' => 'description',
            'price' => 10000.00,
            'cost' => 7000.00,
            'quantity' => 4,
            'weight' => 100,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'لبنة',
            'description' => 'description',
            'price' => 20000.00,
            'cost' => 17500.00,
            'quantity' => 4,
            'weight' => 250,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'جبنة',
            'description' => 'description',
            'price' => 20000.00,
            'cost' => 17500.00,
            'quantity' => 4,
            'weight' => 250,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'عصير تفاح',
            'description' => 'description',
            'price' => 10000.00,
            'cost' => 6000.00,
            'quantity' => 7,
            'weight' => 250,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'عصير منجا',
            'description' => 'description',
            'price' => 10000.00,
            'cost' => 6000.00,
            'quantity' => 2,
            'weight' => 250,
            'is_active' => false,
        ]);
    }
}

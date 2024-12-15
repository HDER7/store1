<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $products = Product::all();

        foreach ($products as $product) {
            for ($i = 0; $i < 5; $i++) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $faker->uuid,
                    'color' => $faker->colorName,
                    'size' => $faker->randomElement(['S', 'M', 'L', 'XL']),
                    'stock_quantity' => $faker->numberBetween(0, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

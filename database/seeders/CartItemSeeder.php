<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $variants = ProductVariant::all();
        $carts = ShoppingCart::all();

        for ($i = 0; $i < 20; $i++) {
            CartItem::create([
                'variant_id' => $faker->randomElement($variants)->id,
                'shopping_cart_id' => $carts->random()->id,
                'quantity' => $faker->numberBetween(1, 5),
                'unit_price' => $faker->randomFloat(2, 10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

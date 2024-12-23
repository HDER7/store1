<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'shopping_cart_id' => ShoppingCart::factory(),
            'variant_id' => ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => $this->faker->numberBetween(2, 10, 1000)
        ];
    }
}

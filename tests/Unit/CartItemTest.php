<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_item_belongs_to_cart()
    {
        $cart = ShoppingCart::factory()->create();
        $cartItem = CartItem::factory()->create(['shopping_cart_id' => $cart->id]);

        $this->assertEquals($cart->id, $cartItem->ShoppingCart->id);
    }

    public function test_cart_item_belongs_to_variant()
    {
        $variant = ProductVariant::factory()->create();
        $cartItem = CartItem::factory()->create(['variant_id' => $variant->id]);

        $this->assertEquals($variant->id, $cartItem->variant->id);
    }
}

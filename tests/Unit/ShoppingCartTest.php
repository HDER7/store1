<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_belongs_to_user()
    {
        $user = User::factory()->create();
        $cart = ShoppingCart::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $cart->user->id);
    }

    public function test_cart_can_have_multiple_items()
    {
        $cart = ShoppingCart::factory()->create();
        $items = CartItem::factory(3)->create(['shopping_cart_id' => $cart->id]);

        $this->assertEquals(3, $cart->items()->count());
    }

    public function test_cart_can_calculate_total()
    {
        $cart = ShoppingCart::factory()->create();

        $variant1 = ProductVariant::factory()->create();
        $variant2 = ProductVariant::factory()->create();

        $item1 = CartItem::factory()->create([
            'shopping_cart_id' => $cart->id,
            'variant_id' => $variant1->id,
            'quantity' => 2
        ]);

        $item2 = CartItem::factory()->create([
            'shopping_cart_id' => $cart->id,
            'variant_id' => $variant2->id,
            'quantity' => 1
        ]);

        $total_real = ($item1->quantity * $item1->unit_price)+($item2->quantity * $item2->unit_price);
        $total_cal = 0;
        foreach ($cart->items as $item) {
            $total_cal += $item->quantity * $item->unit_price;
        }

        $this->assertEquals($total_real, $total_cal);
    }
}

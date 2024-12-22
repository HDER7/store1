<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_shopping_cart()
    {
        $user = User::factory()->create();
        $cart = ShoppingCart::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->shoppingCart()->exists());
        $this->assertEquals($cart->user_id, $user->shoppingCart->user_id);
    }

    public function test_user_can_have_multiple_orders()
    {
        $user = User::factory()->create();
        $orders = Order::factory(3)->create(['user_id' => $user->id]);

        $this->assertEquals(3, $user->orders()->count());
    }
}

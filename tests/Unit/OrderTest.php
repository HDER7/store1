<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_can_have_multiple_items()
    {
        $order = Order::factory()->create();
        $items = OrderItem::factory(3)->create(['order_id' => $order->id]);

        $this->assertEquals(3, $order->items()->count());
    }

}

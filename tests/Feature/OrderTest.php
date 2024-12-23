<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_create_order()
    {
        $cart = ShoppingCart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create(['shopping_cart_id' => $cart->id]);


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/orders/create',[
            'payment_method' => 'credit_card',
            'shipping_address' => '123 Main St, City, Country']);

        $response->assertStatus(201);
    }

    public function test_cannot_create_order_with_empty_cart()
    {
        ShoppingCart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/orders/create');

        $response->assertStatus(422);
    }

    public function test_can_list_user_orders()
    {
        $orders = Order::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/orders');


        $response->assertStatus(200)
            ->assertJsonCount(3, 'orders');
    }

    public function test_can_show_single_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/orders/{$order->id}");

        echo $response->getContent();
        $response->assertStatus(200)
            ->assertJson([
                'id' => $order->id
            ]);
    }

    public function test_user_cannot_access_other_users_orders()
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/orders/{$order->id}");


        $response->assertStatus(404);
    }
}

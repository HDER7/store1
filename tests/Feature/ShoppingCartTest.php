<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_add_to_cart()
    {
        $variant = ProductVariant::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2
        ]);

        $response->assertStatus(201);
    }

    public function test_update_cart_item()
    {
        $cart = ShoppingCart::factory()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create(['shopping_cart_id' => $cart->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/cart/update/{$cartItem->id}", [
            'quantity' => 3
        ]);

        $response->assertStatus(200);
        $this->assertEquals(3, $cartItem->fresh()->quantity);
    }

    public function test_can_remove_from_cart()
    {
        $cart = ShoppingCart::factory()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create(['shopping_cart_id' => $cart->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/cart/remove/{$cartItem->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}

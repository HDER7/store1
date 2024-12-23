<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_list_products()
    {
        $products = Product::factory(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_show_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $product->id]);
    }

    public function test_search_products()
    {
        $product1 = Product::factory()->create(['name' => 'Blue Shirt']);
        $product2 = Product::factory()->create(['name' => 'Red Shirt']);

        $response = $this->getJson('/api/v1/products/search?name=Blue');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}

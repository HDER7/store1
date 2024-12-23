<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_have_multiple_variants()
    {
        $product = Product::factory()->create();
        $variants = ProductVariant::factory(3)->create(['product_id' => $product->id]);

        $this->assertEquals(3, $product->variants()->count());
    }

    public function test_product_variant_belongs_to_product()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        $this->assertEquals($product->id, $variant->product->id);
    }
}

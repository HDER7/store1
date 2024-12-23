<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'color' => $this->faker->colorName(),
            'sku' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Psy\Util\Json;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'other_attributes' => Json::encode([
                'brand' => $this->faker->company(),
                'collection' => $this->faker->word(),
                'gender' => $this->faker->randomElement(['male', 'female', 'unisex']),
            ])
        ];
    }
}

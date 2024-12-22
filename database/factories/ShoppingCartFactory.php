<?php

namespace Database\Factories;

use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShoppingCart>
 */
class ShoppingCartFactory extends Factory
{
    protected $model = ShoppingCart::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->boolean()
        ];
    }
}

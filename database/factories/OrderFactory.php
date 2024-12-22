<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'cancelled', 'delivered','refunded']),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'order_date' => $this->faker->date(),
            'shipping_address' => $this->faker->address(),
            'payment_method' => $this->faker->randomElement(['paypal','credit_card','bank_transfer']),
        ];
    }
}

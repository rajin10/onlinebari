<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // overridden by seeder
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->numerify('017########'),
            'email' => fake()->safeEmail(),
            'country' => 'Bangladesh',
            'district' => fake()->city(),
            'address' => fake()->streetAddress(),
            'shipping_method' => 'Home Delivery',
            'payment_method' => 'Cash on Delivery',
            'subtotal' => 0,
            'discount' => 0,
            'total' => 0,
            'cart_type' => '0',
            'status' => fake()->numberBetween(0, 5),
            'pay_staus' => 0,
            'order_id' => (string) fake()->unique()->numberBetween(100000, 999999),
            'invoice' => '#' . fake()->unique()->numberBetween(100000, 999999),
        ];
    }
}

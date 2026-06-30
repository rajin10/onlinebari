<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,    // overridden by seeder
            'product_id' => 1, // overridden by seeder
            'order_id' => null,
            'rating' => fake()->numberBetween(3, 5),
            'body' => fake()->sentence(14),
            'file' => '',
        ];
    }
}

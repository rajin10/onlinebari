<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $adjectives = ['Aurora', 'Helix', 'Nimbus', 'Lumen', 'Cascade', 'Orbit', 'Zephyr', 'Solace', 'Mirage', 'Halo', 'Drift', 'Ember'];
        $forms = ['Swirl', 'Ridge', 'Wave', 'Spiral', 'Twist', 'Bloom', 'Pleat', 'Ripple'];
        $types = ['Table Lamp', 'Lamp Shade', 'Bedside Lamp', 'Ambient Light', 'Pendant Lamp', 'Night Lamp'];

        $title = fake()->randomElement($adjectives) . ' ' . fake()->randomElement($forms) . ' ' . fake()->randomElement($types);
        $price = fake()->numberBetween(1200, 9000);

        return [
            'user_id' => 1,   // overridden by seeder
            'brand_id' => 1,  // overridden by seeder
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'title' => $title,
            'short_description' => fake()->sentence(12),
            'full_description' => fake()->paragraphs(3, true),
            'regular_price' => (string) $price,
            'discount_price' => fake()->boolean(40) ? (string) ($price - fake()->numberBetween(100, 500)) : null,
            'dis_type' => 1,
            'quantity' => fake()->numberBetween(5, 100),
            'unit' => 'pcs',
            'image' => 'default.png',  // overridden by seeder
            'shipping_charge' => 0,
            'point' => 0,
            'reach' => 0,
            'status' => true,
            'is_aproved' => 1,
            'type' => 0,
            'download_able' => 0,
            'refer' => 0,
        ];
    }
}

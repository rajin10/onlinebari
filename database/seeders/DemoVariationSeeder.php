<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Demo data so the homepage product cards' dynamic colour swatches and review
 * ratings (issue #6, item #5) are visibly testable. Idempotent — safe to re-run.
 *
 *   php artisan db:seed --class=DemoVariationSeeder
 */
class DemoVariationSeeder extends Seeder
{
    public function run(): void
    {
        $colorIds = $this->seedColors();

        $userIds = User::pluck('id')->all();
        if (empty($userIds)) {
            $this->command?->warn('DemoVariationSeeder: no users to author reviews; skipping reviews.');
        }

        $bodies = [
            'Exactly as pictured — beautiful glow and solid build.',
            'Looks stunning in the living room. Highly recommend.',
            'Great quality for the price. Fast delivery too.',
            'The craftsmanship is lovely. Very happy with it.',
            'Warm, cozy lighting — even nicer in person.',
        ];

        $products = Product::where('status', true)->orderBy('id')->take(24)->get();

        foreach ($products as $product) {
            // Attach 2–4 random demo colours (keeps existing links; sets pivot data).
            $pick = collect($colorIds)->shuffle()->take(rand(2, 4));
            $product->colors()->syncWithoutDetaching(
                $pick->mapWithKeys(fn ($id) => [$id => ['qnty' => rand(5, 50), 'price' => 0]])->all()
            );

            // Seed a few reviews only when the product has none yet.
            if (! empty($userIds) && $product->reviews()->count() === 0) {
                foreach (range(1, rand(1, 3)) as $n) {
                    Review::create([
                        'user_id' => $userIds[array_rand($userIds)],
                        'product_id' => $product->id,
                        'rating' => rand(3, 5),
                        'body' => $bodies[array_rand($bodies)],
                    ]);
                }
            }
        }

        $this->command?->info('DemoVariationSeeder: colours attached and demo reviews seeded for '.$products->count().' products.');
    }

    /**
     * @return array<int> the demo colour ids
     */
    private function seedColors(): array
    {
        $palette = [
            ['name' => 'Black', 'code' => '#000000'],
            ['name' => 'White', 'code' => '#FFFFFF'],
            ['name' => 'Gold', 'code' => '#FFCC00'],
            ['name' => 'Charcoal', 'code' => '#36454F'],
            ['name' => 'Warm White', 'code' => '#FDF4DC'],
        ];

        $ids = [];
        foreach ($palette as $c) {
            $color = Color::firstOrCreate(
                ['slug' => Str::slug($c['name'])],
                ['name' => $c['name'], 'code' => $c['code'], 'status' => true]
            );
            $ids[] = $color->id;
        }

        return $ids;
    }
}

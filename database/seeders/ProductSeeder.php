<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        if (Product::count() >= 40) {
            return;
        }

        $vendorRoleId = Role::where('slug', 'vendor')->value('id') ?? 2;
        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;

        $vendorIds = User::where('role_id', $vendorRoleId)->pluck('id')->all();
        $customerIds = User::where('role_id', $userRoleId)->pluck('id')->all();
        $brandIds = Brand::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();
        $images = $this->imagePool('product');

        if ($vendorIds === [] || $brandIds === [] || $categoryIds === []) {
            return; // prerequisites missing
        }

        Product::withoutSyncingToSearch(function () use ($vendorIds, $customerIds, $brandIds, $categoryIds, $images) {
            for ($i = 0; $i < 45; $i++) {
                $product = Product::factory()->create([
                    'user_id' => fake()->randomElement($vendorIds),
                    'brand_id' => fake()->randomElement($brandIds),
                    'image' => $images === [] ? 'default.png' : fake()->randomElement($images),
                    'reach' => fake()->boolean(35) ? fake()->numberBetween(1, 500) : 0,
                ]);

                $product->categories()->attach(
                    fake()->randomElements($categoryIds, fake()->numberBetween(1, min(2, count($categoryIds))))
                );

                foreach (range(1, fake()->numberBetween(2, 3)) as $g) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'name' => $images === [] ? 'default.png' : fake()->randomElement($images),
                    ]);
                }

                if ($customerIds !== []) {
                    Review::factory()->count(fake()->numberBetween(1, 4))->create([
                        'product_id' => $product->id,
                        'user_id' => fake()->randomElement($customerIds),
                    ]);
                }
            }
        });
    }
}

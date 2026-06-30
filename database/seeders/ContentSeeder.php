<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Slider;
use App\Models\User;
use Database\Seeders\Concerns\SeedsAssets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentSeeder extends Seeder
{
    use SeedsAssets;

    public function run(): void
    {
        // Sliders — first one featured.
        $sliderImages = array_values($this->imagePool('slider'));
        if ($sliderImages === []) {
            $sliderImages = ['default.png'];
        }
        foreach ($sliderImages as $i => $img) {
            Slider::firstOrCreate(
                ['image' => $img],
                ['url' => '#', 'status' => 1, 'is_feature' => $i === 0 ? 1 : 0, 'is_pop' => 0, 'is_sub' => 0]
            );
        }

        // Banners.
        $bannerImages = $this->imagePool('banner');
        if ($bannerImages === []) {
            $bannerImages = ['default.png'];
        }
        foreach ($bannerImages as $img) {
            Banner::firstOrCreate(['image' => $img], ['url' => '#', 'status' => 1, 'is_feature' => 0, 'is_pop' => 0, 'is_sub' => 0]);
        }

        // Coupons.
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            ['description' => '10% off your first order', 'discount_type' => 'percent', 'discount' => 10, 'limit_per_user' => 1, 'total_use_limit' => 100, 'available_limit' => 100, 'expire_date' => now()->addYear()->toDateString(), 'status' => 1]
        );
        Coupon::firstOrCreate(
            ['code' => 'LIGHT500'],
            ['description' => '৳500 off orders over ৳5000', 'discount_type' => 'amount', 'discount' => 500, 'limit_per_user' => 1, 'total_use_limit' => 100, 'available_limit' => 100, 'expire_date' => now()->addMonths(6)->toDateString(), 'status' => 1]
        );

        // Blogs — copy a real image into uploads/blogs/ so the view path resolves.
        $adminId = User::where('role_id', 1)->value('id') ?? User::value('id');
        $categoryId = Category::value('id');

        $thumb = 'demo-blog.webp';
        $blogDir = public_path('uploads/blogs');
        File::ensureDirectoryExists($blogDir);
        $source = collect(File::files(public_path('uploads/slider')))->first();
        if ($source && ! File::exists($blogDir.'/'.$thumb)) {
            File::copy($source->getPathname(), $blogDir.'/'.$thumb);
        }

        $posts = [
            'Choosing the Right Table Lamp for Cozy Evenings',
            'How 3D-Printed Shades Transform Ambient Light',
            'Care Tips for Your Decorative Lighting',
        ];
        foreach ($posts as $title) {
            Blog::firstOrCreate(
                ['title' => $title],
                ['user_id' => $adminId, 'category_id' => $categoryId, 'thumbnail' => $thumb, 'description' => fake()->paragraphs(4, true), 'status' => 1, 'is_admin' => 1]
            );
        }
    }
}

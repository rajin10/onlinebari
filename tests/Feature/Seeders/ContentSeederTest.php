<?php

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Coupon;
use App\Models\Slider;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(CatalogSeeder::class);
});

it('seeds homepage content', function () {
    $this->seed(ContentSeeder::class);

    expect(Slider::where('status', true)->count())->toBeGreaterThanOrEqual(1);
    expect(Slider::where('is_feature', 1)->count())->toBeGreaterThanOrEqual(1);
    expect(Banner::where('status', true)->count())->toBeGreaterThanOrEqual(1);
    expect(Coupon::where('discount_type', 'percent')->count())->toBe(1);
    expect(Blog::count())->toBe(3);
});

it('ContentSeeder is idempotent', function () {
    $this->seed(ContentSeeder::class);
    $this->seed(ContentSeeder::class);

    expect(Coupon::where('code', 'WELCOME10')->count())->toBe(1);
});

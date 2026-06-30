<?php

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(CatalogSeeder::class);
    $this->seed(VendorSeeder::class);
    $this->seed(CustomerSeeder::class);
});

it('seeds products wired to brand, vendor, categories, images, reviews', function () {
    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBeGreaterThanOrEqual(40);
    expect(Product::where('status', true)->count())->toBe(Product::count());
    expect(Product::where('reach', '>', 0)->count())->toBeGreaterThan(0);
    expect(ProductImage::count())->toBeGreaterThan(Product::count());
    expect(Review::count())->toBeGreaterThan(Product::count());

    $p = Product::with(['brand', 'user', 'categories'])->first();
    expect($p->brand)->not->toBeNull();
    expect($p->user)->not->toBeNull();
    expect($p->categories)->not->toBeEmpty();
    expect($p->image)->not->toBe('');
});

it('ProductSeeder is idempotent', function () {
    $this->seed(ProductSeeder::class);
    $count = Product::count();
    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBe($count);
});

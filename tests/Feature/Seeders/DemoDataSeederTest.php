<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoDataSeeder;

it('full DatabaseSeeder populates a coherent storefront', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Setting::where('name', 'logo')->value('value'))->toBe('logo.svg');
    expect(Product::count())->toBeGreaterThanOrEqual(40);
    expect(Order::count())->toBeGreaterThanOrEqual(30);
    expect(User::count())->toBeGreaterThan(25); // admin + vendors + 25 customers
    expect(\Illuminate\Support\Facades\DB::table('category_product')->count())->toBeGreaterThan(0);
});

it('DemoDataSeeder skips demo data in production', function () {
    app()['env'] = 'production';

    $this->seed(DemoDataSeeder::class);

    expect(Product::count())->toBe(0);

    app()['env'] = 'testing';
});

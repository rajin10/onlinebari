<?php

use App\Models\Order;
use App\Models\OrderDetails;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VendorSeeder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    $this->seed(CatalogSeeder::class);
    $this->seed(VendorSeeder::class);
    $this->seed(CustomerSeeder::class);
    $this->seed(ProductSeeder::class);
});

it('seeds orders with line items and totals', function () {
    $this->seed(OrderSeeder::class);

    expect(Order::count())->toBe(30);
    expect(OrderDetails::count())->toBeGreaterThanOrEqual(30);
    expect(Order::where('total', '>', 0)->count())->toBe(30);

    $detail = OrderDetails::first();
    expect($detail->seller_id)->toBeGreaterThan(0);
    expect($detail->color)->toBe('N/A');
});

it('OrderSeeder is idempotent', function () {
    $this->seed(OrderSeeder::class);
    $this->seed(OrderSeeder::class);

    expect(Order::count())->toBe(30);
});

<?php

use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;

// Regression: the vendor order list (vendor/order) looks up a per-vendor `multi_order`
// row for each order's totals. An order can contain the vendor's product (so it appears
// in the list) yet have no matching `multi_order` row — the view must not 500 on that.

it('renders the vendor order list when an order has no multi_order row', function () {
    $this->withoutVite();
    $this->seed(RoleSeeder::class);

    $vendor = User::factory()->create([
        'role_id' => 2, // Vendor
        'is_approved' => true,
        'status' => true,
    ]);

    $brand = Brand::create(['name' => 'Test Brand', 'slug' => 'test-brand']);
    $product = Product::factory()->create(['user_id' => $vendor->id, 'brand_id' => $brand->id]);

    // An order that contains the vendor's product (so the controller includes it in
    // $orders) but has NO multi_order row for this vendor.
    $order = Order::factory()->create([
        'user_id' => $vendor->id,
        'total' => 500,
        'discount' => 50,
        'status' => 0,
    ]);

    OrderDetails::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'seller_id' => $vendor->id,
        'title' => $product->title,
        'color' => 'default',
        'size' => 'default',
        'qty' => 1,
        'price' => 500,
        'total_price' => 500,
        'g_total' => 500,
    ]);

    $this->actingAs($vendor)->get('/vendor/order')->assertOk();
});

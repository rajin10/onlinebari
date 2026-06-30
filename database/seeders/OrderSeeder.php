<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::count() >= 30) {
            return;
        }

        $userRoleId = Role::where('slug', 'user')->value('id') ?? 3;
        $customers = User::where('role_id', $userRoleId)->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach (range(1, 30) as $n) {
            $customer = $customers->random();

            $order = Order::factory()->create([
                'user_id' => $customer->id,
                'first_name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ]);

            $items = $products->random(min(fake()->numberBetween(1, 3), $products->count()));
            $subtotal = 0;

            foreach ($items as $product) {
                $qty = fake()->numberBetween(1, 3);
                $price = (float) ($product->discount_price ?: $product->regular_price);
                $line = $price * $qty;
                $subtotal += $line;

                OrderDetails::create([
                    'order_id' => $order->id,
                    'seller_id' => $product->user_id,
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'color' => 'N/A',
                    'size' => 'N/A',
                    'qty' => $qty,
                    'price' => $price,
                    'total_price' => $line,
                    'g_total' => $line,
                ]);
            }

            $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);
        }
    }
}

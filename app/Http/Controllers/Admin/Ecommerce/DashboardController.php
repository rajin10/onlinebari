<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // amount, vendor, customer
        $products = DB::table('products')->count();
        $quantity = DB::table('products')->sum('quantity');
        $orders = DB::table('orders')->count();
        $pending_orders = DB::table('orders')->where('status', 0)->count();
        $processing_orders = DB::table('orders')->where('status', 1)->count();
        $cancel_orders = DB::table('orders')->where('status', 2)->count();
        $delivered_orders = DB::table('orders')->where('status', 3)->count();
        $vendor_amount = DB::table('vendor_accounts')->where('vendor_id', '!=', 1)->sum('amount');
        $admin_amount = DB::table('vendor_accounts')->where('vendor_id', 1)->sum('amount');
        $pending_amount = DB::table('vendor_accounts')->where('vendor_id', 1)->sum('pending_amount');
        $vendor_pamount = DB::table('vendor_accounts')->where('vendor_id', '!=', 1)->sum('pending_amount');
        $vendors = DB::table('users')->where('role_id', 2)->count();
        $customers = DB::table('users')->where('role_id', 3)->count();
        $commission = DB::table('commissions')->where('status', true)->sum('amount');
        $return_orders = DB::table('orders')->where('status', 4)->count();

        $revenue_today = DB::table('orders')
            ->whereDate('created_at', today())
            ->sum('total');

        $revenue_monthly = DB::table('orders')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $new_customers = DB::table('users')
            ->where('role_id', 3)
            ->whereDate('created_at', today())
            ->count();

        $staff_count = DB::table('users')
            ->where('role_id', 1)
            ->count();

        $fraud_orders = 0;

        $daily_visits = DB::table('active_visitors')
            ->whereDate('created_at', today())
            ->count();

        $conversion_rate = $daily_visits > 0
            ? round(($orders / $daily_visits) * 100, 1)
            : 0;

        $bounce_rate = 0;

        return view('admin.e-commerce.dashboard', compact(
            'return_orders',
            'revenue_today',
            'revenue_monthly',
            'new_customers',
            'staff_count',
            'fraud_orders',
            'conversion_rate',
            'daily_visits',
            'bounce_rate',
            'products',
            'pending_amount',
            'vendor_pamount',
            'quantity',
            'orders',
            'pending_orders',
            'processing_orders',
            'cancel_orders',
            'delivered_orders',
            'vendor_amount',
            'admin_amount',
            'vendors',
            'customers',
            'commission'
        ));
    }
}

@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('css')
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div>
            <?php

use App\Models\Product;

            $low_products = Product::where('quantity', '<', '6')->where('user_id', auth()->id())->count();
            if ($low_products > 0) {
                ?>
            <a href="{{ route('admin.low.product') }}"
               class="mb-4 block w-full rounded bg-red-600 px-6 py-2 text-sm text-white hover:bg-red-700">
                You have {{ $low_products }} low quantity product.
            </a>
            <?php }?>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-800">Dashboard</h1>
                <ol class="flex items-center gap-1 text-sm text-slate-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Home</a></li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Live Active Visitors --}}
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="p-4">
                    <p class="mb-1 text-xs text-slate-500">Live Active Visitors</p>
                    <h3 class="mb-0 text-3xl font-bold text-slate-800" id="live-active-visitors">0</h3>
                    <small class="text-green-600">Realtime website users</small>
                </div>
            </div>

            <x-ui.stat-tile variant="info"
                :value="$products"
                label="Total Products"
                icon="fas fa-procedures"
                :href="routeHelper('product')" />

            <x-ui.stat-tile variant="warning"
                :value="$quantity"
                label="Product Qty"
                icon="fas fa-sort-numeric-down-alt"
                :href="routeHelper('product')" />

            <x-ui.stat-tile variant="success"
                :value="$orders"
                label="Total Orders"
                icon="fas fa-shopping-cart"
                :href="routeHelper('order')" />

            <x-ui.stat-tile variant="info"
                :value="$pending_orders"
                label="Pending Orders"
                icon="fas fa-hourglass-start"
                :href="routeHelper('order/pending')" />

            <x-ui.stat-tile variant="primary"
                :value="$processing_orders"
                label="Processing Orders"
                icon="fas fa-running"
                :href="routeHelper('order/processing')" />

            <x-ui.stat-tile variant="danger"
                :value="$cancel_orders"
                label="Cancel Orders"
                icon="fas fa-window-close"
                :href="routeHelper('order/cancel')" />

            <x-ui.stat-tile variant="success"
                :value="$delivered_orders"
                label="Delivered Orders"
                icon="fas fa-thumbs-up"
                :href="routeHelper('order/delivered')" />

            <x-ui.stat-tile variant="primary"
                :value="$vendor_amount"
                label="Vendor Amount"
                icon="fas fa-money-check-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="primary"
                :value="$vendor_pamount"
                label="Vendor Pending Amount"
                icon="fas fa-money-check-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="info"
                :value="$admin_amount"
                label="Self Amount"
                icon="fas fa-money-bill-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="info"
                :value="$pending_amount"
                label="Self Pending"
                icon="fas fa-money-bill-alt"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="primary"
                :value="$vendors"
                label="Total Vendor"
                icon="fas fa-users-cog"
                :href="routeHelper('vendor')" />

            <x-ui.stat-tile variant="warning"
                :value="$customers"
                label="Total Customer"
                icon="fas fa-users"
                :href="routeHelper('customer')" />

            <x-ui.stat-tile variant="success"
                :value="$commission"
                label="Total Commission"
                icon="fas fa-money-bill"
                :href="route('admin.comission')" />

        </div>
    </section>

@endsection

@push('js')
<script>
    (function() {
        const url = "{{ route('visitor.count') }}";

        function updateCount() {
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const el = document.getElementById('live-active-visitors');
                        if (el) el.innerText = data.active_count;
                    }
                })
                .catch(() => {});
        }

        updateCount();
        setInterval(updateCount, 5000);
    })();
</script>
@endpush

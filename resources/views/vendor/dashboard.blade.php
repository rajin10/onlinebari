@extends('layouts.vendor.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 flex items-baseline justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Dashboard</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700">Home</a>
    </div>

    @if (auth()->user()->shop_info->name == 'null_wait')
        <a href="profile/update" class="mb-4 block rounded-lg bg-tile-danger px-4 py-3 text-center text-lg font-bold text-white">
            Please Complete Your profile.
        </a>
    @endif
    @if (auth()->user()->is_approved == 0)
        <p class="mb-4 text-center text-xl text-red-600">
            Your Account is Under Review. You Cant Upload Product At This Time
        </p>
    @endif

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-ui.stat-tile variant="info"    :value="$products"          label="Total Products"   icon="fas fa-procedures"        :href="routeHelper('product')" />
        <x-ui.stat-tile variant="warning" :value="$quantity"          label="Product Qty"      icon="fas fa-sort-numeric-down-alt" :href="routeHelper('product')" />
        <x-ui.stat-tile variant="success" :value="$orders"            label="Total Orders"     icon="fas fa-shopping-cart"     :href="routeHelper('order')" />
        <x-ui.stat-tile variant="info"    :value="$pending_orders"    label="Pending Orders"   icon="fas fa-hourglass-start"   :href="routeHelper('order/pending')" />
        <x-ui.stat-tile variant="primary" :value="$processing_orders" label="Processing Orders" icon="fas fa-running"          :href="routeHelper('order/processing')" />
        <x-ui.stat-tile variant="danger"  :value="$cancel_orders"     label="Cancel Orders"    icon="fas fa-window-close"      :href="routeHelper('order/cancel')" />
        <x-ui.stat-tile variant="primary" :value="$sihpping_order"    label="Shipping Orders"  icon="fas fa-plane"             :href="routeHelper('order/')" />
        <x-ui.stat-tile variant="danger"  :value="$refund_orders"     label="Refund Orders"    icon="fas fa-running"           :href="routeHelper('order/')" />
        <x-ui.stat-tile variant="success" :value="$delivered_orders"  label="Delivered Orders" icon="fas fa-thumbs-up"         :href="routeHelper('order/delivered')" />
        <x-ui.stat-tile variant="info"    :value="$amount"            label="Total Amount"     icon="fas fa-money-bill-alt" />
        <x-ui.stat-tile variant="warning" :value="$pending_amount"    label="Pending Amount"   icon="fas fa-money-bill-alt" />
    </div>
@endsection

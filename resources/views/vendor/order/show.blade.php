@extends('layouts.vendor.app')

@section('title', 'Order Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Order</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
                <span class="mx-1">/</span>
                <span>Order Information</span>
            </nav>
        </div>
    </section>

    {{-- Main content --}}
    <section class="space-y-6">

        {{-- Customer Information card --}}
        @php
            $order_dt = DB::table('multi_order')
                ->where('order_id', $order->id)
                ->where('vendor_id', auth()->id())
                ->first();
        @endphp

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Customer Information</h3>
                    <div class="flex items-center gap-2">
                        @if ($order_dt->status == 0)
                            <x-ui.button
                                variant="primary"
                                size="sm"
                                :href="routeHelper('order/status/processing/' . $order->id)"
                                title="Processing"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-running"></i>
                                Processing
                            </x-ui.button>
                        @elseif ($order_dt->status == 1)
                            <x-ui.button
                                variant="primary"
                                size="sm"
                                :href="routeHelper('order/status/shipping/' . $order->id)"
                                title="Shipping"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-running"></i>
                                Shipping
                            </x-ui.button>
                        @endif

                        <x-ui.button
                            variant="warning"
                            :href="routeHelper('order/print/' . $order->id)"
                            rel="noopener"
                            target="_blank"
                        >
                            <i class="fas fa-print"></i> Print
                        </x-ui.button>
                    </div>
                </div>
            </x-slot:header>

            <x-ui.table>
                <tbody>
                    <tr>
                        <th>Customer Name</th>
                        <td>{{ $order->first_name }}</td>
                        <th>Order ID</th>
                        <td>{{ $order->order_id }}</td>
                    </tr>
                    <tr>
                        <th>Invoice</th>
                        <td>{{ $order->invoice }}</td>
                        <th>Company Name</th>
                        <td>{{ $order->company_name }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $order->country }}</td>
                        <th>Address</th>
                        <td>{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <th>Town</th>
                        <td>{{ $order->town }}</td>
                        <th>District</th>
                        <td>{{ $order->district }}</td>
                    </tr>
                    <tr>
                        <th>Post Code</th>
                        <td>{{ $order->post_code }}</td>
                        <th>Phone</th>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $order->email }}</td>
                        <th>Shipping Method</th>
                        <td>{{ $order->shipping_method }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td colspan="3">{{ $order->payment_method }}</td>
                    </tr>
                    @if ($order->payment_method == 'Bkash' || $order->payment_method == 'Nagad' || $order->payment_method == 'Rocket')
                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $order->mobile_number }}</td>
                            <th>Transaction ID</th>
                            <td>{{ $order->transaction_id }}</td>
                        </tr>
                    @elseif ($order->payment_method == 'Bank')
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $order->bank_name }}</td>
                            <th>Account Number</th>
                            <td>{{ $order->account_number }}</td>
                        </tr>
                        <tr>
                            <th>Holder Name</th>
                            <td>{{ $order->holder_name }}</td>
                            <th>Branch Name</th>
                            <td>{{ $order->branch_name }}</td>
                        </tr>
                        <tr>
                            <th>Routing Number</th>
                            <td colspan="3">{{ $order->routing_number }}</td>
                        </tr>
                    @endif
                    @php
                        $total = 0;
                        $ids = [];
                    @endphp
                    @foreach ($order->orderDetails as $key => $item)
                        @if ($item->product->user_id == auth()->id())
                            @php
                                $total += $item->total_price;
                            @endphp
                        @endif
                        @php
                            $whole = \App\Models\Product::find($item->product_id);
                            if (!in_array("$whole->user_id", $ids)) {
                                $ids[] = $whole->user_id;
                            }
                        @endphp
                    @endforeach
                    <?php
                    $seller_count = count($ids);
                    ?>
                    <tr>
                        <th>Coupon Code</th>
                        <td>{{ $order->coupon_code }}</td>
                        <th>Subtotal</th>
                        <td>{{ $total }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                    </tr>
                    @php
                        $part = DB::table('multi_order')
                            ->where('order_id', $order->id)
                            ->where('vendor_id', auth()->id())
                            ->sum('partial_pay');
                        $discount = DB::table('multi_order')
                            ->where('order_id', $order->id)
                            ->where('vendor_id', auth()->id())
                            ->sum('discount');
                    @endphp
                    <tr>
                        <th>Shipping Charge</th>
                        <td>{{ $order->shipping_charge / $seller_count }}
                            <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                        <th>Discount</th>
                        <td>{{ $discount }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                    </tr>

                    <tr>
                        <th>partial pay </th>
                        <td>{{ $part }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                        <th>Total</th>
                        <td>{{ $total + $order->shipping_charge / $seller_count }}
                            <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                    </tr>

                    <tr>
                        <th>Due</th>
                        <td>{{ $total + $order->shipping_charge / $seller_count - $part - $discount }}
                            <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                        <th>Status</th>
                        <td>
                            @if ($order_dt->status == 0)
                                <x-ui.badge variant="warning">Pending</x-ui.badge>
                            @elseif ($order_dt->status == 1)
                                <x-ui.badge variant="primary">Processing</x-ui.badge>
                            @elseif ($order_dt->status == 2)
                                <x-ui.badge variant="danger">Canceled</x-ui.badge>
                            @elseif ($order_dt->status == 4)
                                <x-ui.badge class="bg-[#7db1b1] text-white">Shipping</x-ui.badge>
                            @elseif ($order_dt->status == 5)
                                <x-ui.badge variant="danger">Refund</x-ui.badge>
                            @else
                                <x-ui.badge variant="success">Delivered</x-ui.badge>
                            @endif
                        </td>
                    </tr>
                    @if ($order_dt->status == 5)
                        <tr>
                            <th>Refund Method</th>
                            <td>{{ $order->refund_method }}</td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
        </x-ui.card>

        {{-- Order Products card --}}
        <x-ui.card>
            <x-slot:header>
                <h2 class="font-semibold text-slate-900">Order Products</h2>
            </x-slot:header>

            <div class="overflow-x-auto">
                <x-ui.table>
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Product</th>
                            <th>Title</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $key => $item)
                            @if ($item->product->user_id == auth()->id())
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                            alt="Product Image" width="80px" height="80px">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product.show', $item->product->id) }}"
                                            target="_blank">{{ $item->title }}</a>
                                    </td>
                                    <td>{{ $item->size }}</td>
                                    <td>{{ $item->color }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </x-ui.table>
            </div>
        </x-ui.card>

    </section>

@endsection

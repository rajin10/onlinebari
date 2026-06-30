@extends('layouts.admin.app')

@section('title', 'Order Information')

@section('content')
    {{-- Page Header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Order</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Order Information</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="space-y-4">

        {{-- Fraud / Risk Intelligence --}}
        @if ($order->fraud_checked_at || $order->fraud_risk_level)
            @php
                $rl = $order->fraud_risk_level;
                $rv = $rl === 'High' ? 'danger' : ($rl === 'Medium' ? 'warning' : ($rl === 'Low' ? 'success' : 'neutral'));
            @endphp
            <div class="rounded-lg border {{ $order->is_flagged ? 'border-red-300' : 'border-slate-200' }} bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                    <h3 class="font-medium text-slate-900">Fraud Intelligence <small class="font-normal text-slate-500">(courier history + this shop)</small></h3>
                    <div class="flex items-center gap-2">
                        <x-ui.badge :variant="$rv">Risk: {{ $rl ?? 'N/A' }}</x-ui.badge>
                        @if ($order->is_flagged)
                            <x-ui.badge variant="danger">🚩 Flagged for review</x-ui.badge>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 p-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="rounded-lg bg-slate-50 p-3 text-center">
                        <div class="text-xl font-bold text-slate-800">{{ $order->fraud_total_orders ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Total Orders</div>
                    </div>
                    <div class="rounded-lg bg-green-50 p-3 text-center">
                        <div class="text-xl font-bold text-green-700">{{ $order->fraud_success_orders ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Successful</div>
                    </div>
                    <div class="rounded-lg bg-amber-50 p-3 text-center">
                        <div class="text-xl font-bold text-amber-700">{{ $order->fraud_pending_orders ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Pending</div>
                    </div>
                    <div class="rounded-lg bg-red-50 p-3 text-center">
                        <div class="text-xl font-bold text-red-700">{{ $order->fraud_cancelled_orders ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Cancelled</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3 text-center">
                        <div class="text-xl font-bold text-slate-800">{{ $order->fraud_success_rate !== null ? $order->fraud_success_rate . '%' : 'N/A' }}</div>
                        <div class="text-xs text-slate-500">Success Rate</div>
                    </div>
                </div>
                @if ($order->fraud_checked_at)
                    <div class="border-t border-slate-200 px-4 py-2 text-xs text-slate-400">Checked {{ $order->fraud_checked_at->diffForHumans() }}</div>
                @endif
            </div>
        @endif

        {{-- Customer Information Card --}}
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">Customer Information</h3>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($order->status != 5)
                        @if ($order->status != 2)
                            @if ($order->status != 3)
                                <x-ui.button
                                    :href="route('admin.order.pay', ['id' => $order->id])"
                                    :variant="$order->pay_staus == 1 ? 'danger' : 'success'"
                                    size="sm"
                                    :title="$order->pay_staus == 1 ? 'Unpaid' : 'Paid'"
                                >
                                    <i class="fas fa-money-bill"></i>
                                    @if ($order->pay_staus == 1)
                                        Unpaid
                                    @else
                                        Paid
                                    @endif
                                </x-ui.button>
                            @endif
                        @endif

                        @if (setting('STEEDFAST_STATUS') == 1 && $order->status != 9)
                            <form action="{{ route('admin.setting.courier.sendsteedfast') }}" method="POST">
                                @csrf
                                <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                                <input type="hidden" name="recipient_name" value="{{ $order->first_name }}">
                                <input type="hidden" name="recipient_phone" value="{{ $order->phone }}">
                                <input type="hidden" name="recipient_address"
                                    value="{{ $order->address . ', ' . $order->town . ', ' . $order->district . ', ' . $order->post_code }}">
                                @if ($order->pay_staus == 1)
                                    <input type="hidden" name="cod_amount" value="0.00">
                                @else
                                    <input type="hidden" name="cod_amount" value="{{ $order->total }}">
                                @endif
                                <input type="hidden" name="note" value="N/A">
                                <x-ui.button type="submit" variant="info" size="sm">Send Courier</x-ui.button>
                            </form>
                        @else
                            <x-ui.button variant="info" size="sm" type="button">Courierd Already</x-ui.button>
                        @endif

                        <x-ui.button
                            :href="routeHelper('order/status/processing/' . $order->id)"
                            variant="primary"
                            size="sm"
                            title="Processing"
                            onclick="alert('Are you sure change status this order?')"
                        >
                            <i class="fas fa-running"></i>
                            Processing
                        </x-ui.button>

                        @if ($order->status == 6)
                            <x-ui.button
                                :href="routeHelper('order/status/return_req_accept/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Accept return request"
                                onclick="alert('Return process are start')"
                            >
                                Return Accept
                            </x-ui.button>
                        @elseif ($order->status == 7)
                            <x-ui.button
                                :href="routeHelper('order/status/return_complete/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Complete the return process, you got the product from customer as a return completely."
                                onclick="alert('Complete the return, you got the product from customer?')"
                            >
                                Return Complete
                            </x-ui.button>
                        @elseif ($order->status != 2 && $order->status != 3 && $order->status != 6 && $order->status != 7 && $order->status != 8)
                            <x-ui.button
                                :href="routeHelper('order/status/shipping/' . $order->id)"
                                variant="info"
                                size="sm"
                                id="btnShipping"
                                title="Shipping"
                                onclick="return confirm('Are you sure Shipping this order?')"
                            >
                                <i class="fas fa-plane"></i> Shipping
                            </x-ui.button>

                            <x-ui.button
                                :href="routeHelper('order/status/delivered/' . $order->id)"
                                variant="success"
                                size="sm"
                                title="Delivered"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-thumbs-up"></i>
                                Delivered
                            </x-ui.button>
                        @endif

                        @if ($order->status != 3 && $order->status != 2)
                            <x-ui.button
                                :href="routeHelper('order/status/cancel/' . $order->id)"
                                variant="warning"
                                size="sm"
                                title="Cancel"
                                onclick="alert('Are you sure change status this order?')"
                            >
                                <i class="fas fa-window-close"></i>
                                Cancel
                            </x-ui.button>
                        @endif
                    @endif

                    @if ($order->status == 3)
                        <x-ui.button
                            variant="warning"
                            size="sm"
                            type="button"
                            @click="$dispatch('open-modal-refund')"
                        >
                            Refund
                        </x-ui.button>
                    @endif

                    @if ($order->status == 2)
                        <x-ui.button
                            variant="warning"
                            size="sm"
                            type="button"
                            @click="$dispatch('open-modal-refund2')"
                        >
                            Refund
                        </x-ui.button>
                    @endif

                    <x-ui.button
                        :href="route('admin.order.delete', ['did' => $order->id])"
                        variant="danger"
                        size="sm"
                    >
                        <i class="nav-icon fas fa-trash-alt"></i> Delete
                    </x-ui.button>

                    <x-ui.button
                        :href="routeHelper('order/print/' . $order->id)"
                        variant="secondary"
                        size="sm"
                        target="_blank"
                        rel="noopener"
                    >
                        <i class="fas fa-print"></i> Print
                    </x-ui.button>
                </div>
            </div>

            <div class="p-4">
                <x-ui.table>
                    <tbody>
                        @if (!empty($order->meet_time))
                            <tr>
                                <th>Meet Time</th>
                                <td>{{ $order->meet_time }}</td>
                            </tr>
                        @endif
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
                        <tr>
                            <th>Coupon Code</th>
                            <td>{{ $order->coupon_code }}</td>
                            <th>Subtotal</th>
                            <td>{{ $order->subtotal }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Shipping Charge</th>
                            <td>{{ $order->shipping_charge }}
                                <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong>
                            </td>
                            <th>Discount</th>
                            <td>{{ $order->discount }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Payment Status</th>
                            <td>{{ $order->pay_staus == 1 ? 'Paid' : 'Unpaid' }} </td>
                            <th>Payment Date</th>
                            <td>{{ $order->pay_date }} </td>
                        </tr>
                        <tr>
                            <th>Partial Payment</th>
                            <td>
                                @php
                                    $part = App\Models\PartialPayment::where('order_id', $order->id)
                                        ->where('status', 1)
                                        ->sum('amount');
                                    $ds = $order->total;
                                @endphp
                                {{ $part }}<strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong>
                            </td>
                            <th>Due</th>
                            <td> {{ $order->total - $part }}
                                <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>{{ $ds }} <strong>{{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td>
                            <th>Status</th>
                            <td>
                                @if ($order->status == 0)
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @elseif ($order->status == 1)
                                    <x-ui.badge variant="primary">Processing</x-ui.badge>
                                @elseif ($order->status == 2)
                                    <x-ui.badge variant="danger">Canceled</x-ui.badge>
                                @elseif ($order->status == 5)
                                    <x-ui.badge variant="danger">Refund</x-ui.badge>
                                @elseif ($order->status == 4)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Shipping</span>
                                @elseif ($order->status == 6)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Return Request By User</span>
                                @elseif ($order->status == 7)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Return process accept by Owner</span>
                                @elseif ($order->status == 8)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Returned</span>
                                @elseif ($order->status == 9)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Sended to Courier</span>
                                @elseif ($order->status == 3)
                                    <x-ui.badge variant="success">Delivered</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                        @if ($order->status == 5)
                            <tr>
                                <th>Refund Method</th>
                                <td>{{ $order->refund_method }}</td>
                            </tr>
                        @endif
                    </tbody>
                </x-ui.table>
            </div>
        </div>

        {{-- Refund Modal (status == 3 / delivered) --}}
        <x-ui.modal name="refund" title="Refund">
            <form method="post" action="{{ route('admin.refund') }}">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <x-ui.input name="amount" type="text" placeholder="amount" />
                </div>
                <div class="mb-4">
                    <x-ui.select name="method">
                        <option value="wallate">Wallate</option>
                        <option value="Bank">Bank</option>
                        <option value="Bkash">Bkash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Cash">Cash</option>
                    </x-ui.select>
                </div>
                <x-ui.button type="submit" variant="primary">Refund</x-ui.button>
            </form>
            <x-slot:footer>
                <x-ui.button type="button" variant="secondary" @click="$dispatch('close-modal-refund')">Close</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- Refund Modal (status == 2 / canceled) --}}
        <x-ui.modal name="refund2" title="Refund">
            <form method="post" action="{{ route('admin.refund_2') }}">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <x-ui.input name="amount" type="text" placeholder="amount" />
                </div>
                <div class="mb-4">
                    <x-ui.select name="method">
                        <option value="wallate">Wallate</option>
                        <option value="Bank">Bank</option>
                        <option value="Bkash">Bkash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Cash">Cash</option>
                    </x-ui.select>
                </div>
                <x-ui.button type="submit" variant="primary">Refund</x-ui.button>
            </form>
            <x-slot:footer>
                <x-ui.button type="button" variant="secondary" @click="$dispatch('close-modal-refund2')">Close</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- Order Products Card --}}
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <h2 class="font-medium text-slate-900">Order Products</h2>
            </div>
            <div class="p-4">
                @php
                    $vendors = DB::table('multi_order')->where('order_id', $order->id)->get();
                @endphp

                @foreach ($vendors as $key => $vendor)
                    @php($us = App\Models\User::find($vendor->vendor_id))

                    {{-- Vendor summary row --}}
                    <div class="mb-2 flex flex-wrap items-center gap-4 rounded-md bg-green-50/60 px-3 py-2 text-sm">
                        <div>Seller:{{ $us->name }}</div>
                        <div>Total:{{ $vendor->total }}</div>
                        <div>Payment:{{ $vendor->partial_pay }}</div>
                        <div>Discount:{{ $vendor->discount }}</div>
                        <div class="flex items-center gap-1">
                            Status:
                            @if ($vendor->status == 0)
                                <x-ui.badge variant="warning">Pending</x-ui.badge>
                            @elseif ($vendor->status == 1)
                                <x-ui.badge variant="primary">Processing</x-ui.badge>
                            @elseif ($vendor->status == 2)
                                <x-ui.badge variant="danger">Canceled</x-ui.badge>
                            @elseif ($vendor->status == 4)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Shipping</span>
                            @elseif ($vendor->status == 5)
                                <x-ui.badge variant="danger">Refund</x-ui.badge>
                            @elseif ($order->status == 6)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Return Request By User</span>
                            @elseif ($order->status == 7)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Return process accept by Owner</span>
                            @elseif ($order->status == 8)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Returned</span>
                            @elseif ($order->status == 3)
                                <x-ui.badge variant="success">Delivered</x-ui.badge>
                            @endif
                        </div>
                    </div>

                    {{-- Sub-status action links --}}
                    <div class="mb-3 flex flex-wrap gap-2 text-sm">
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 1, 'vendor' => $vendor->vendor_id]) }}" class="text-primary hover:underline">Processing</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 4, 'vendor' => $vendor->vendor_id]) }}" class="text-primary hover:underline">Shipping</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 2, 'vendor' => $vendor->vendor_id]) }}" class="text-primary hover:underline">Canceled</a>
                        <a href="{{ route('admin.order.subStatus', ['id' => $order->id, 'status' => 3, 'vendor' => $vendor->vendor_id]) }}" class="text-primary hover:underline">Delivered</a>
                    </div>

                    <div class="table-responsive mb-6">
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
                                    @if (isset($item->product->user_id) && $item->product->user_id == $vendor->vendor_id)
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
                                            <td><?php
                                            $data = json_decode($item->size);
                                            if ($data != null && $data != '""' && $data != '[]' && $data != '"\"\""') {
                                                foreach ($data as $key => $attr) {
                                                    $value = DB::table('attribute_values')->where('id', $attr)->first();
                                                    $name = DB::table('attributes')->where('slug', $key)->first();
                                                    echo $name?->name;
                                                    if ($name) {
                                                        // echo $vl = $name->name . ':' . $value->name . ', ';
                                                    }
                                                }
                                            }
                                            ?></td>
                                            <td> <?php
                                            if ($item->color != 'blank') {
                                                echo $item->color;
                                            }
                                            ?></td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->total_price }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </x-ui.table>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
@endsection

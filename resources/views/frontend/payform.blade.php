@extends('layouts.frontend.app')

@section('title', 'Pay for Order #' . $order->invoice)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Order Payment</h4>

                    <table class="table table-sm">
                        <tr><th>Invoice</th><td>{{ $order->invoice }}</td></tr>
                        <tr><th>Customer</th><td>{{ $order->first_name }}</td></tr>
                        <tr><th>Subtotal</th><td>{{ number_format($order->subtotal, 2) }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</td></tr>
                        <tr><th>Shipping</th><td>{{ number_format($order->shipping_charge, 2) }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</td></tr>
                        @if ($order->discount > 0)
                        <tr><th>Discount</th><td>- {{ number_format($order->discount, 2) }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</td></tr>
                        @endif
                        <tr><th>Total</th><td><strong>{{ number_format($order->total, 2) }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}</strong></td></tr>
                    </table>

                    <form action="{{ route('order.pay.create', $order->order_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            Pay Now via UddoktaPay
                        </button>
                    </form>

                    <a href="{{ route('order') }}" class="btn btn-secondary btn-block mt-2">Back to Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.frontend.app')

@push('meta')
<meta name='description' content="Download Product File"/>
<meta name='keywords' content="E-commerce, Best e-commerce website" />
@endpush

@section('title', 'Order List')

@section('content')

<div class="customar-dashboard">
    <div class="container">
        <div class="customar-access row">
            <div class="customar-menu col-md-12 py-3">
                <h1>Order Success</h1>
            </div>
            <div class="col-md-12">
                <h4 class="py-2">
                    Your order number is <span class="bg-info text-white p-1">{{ $data['invoice'] }}</span>
                </h4>
                <span class="py-2 px-2 bg-warning">Note: the order number or take a screenshot for next query</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        "event": "purchase",
        "ecommerce": {
            "transaction_id": "{{ $data['invoice'] }}",
            "value": {{ $data['total'] }},
            "currency": "BDT",
            "shipping": {{ $data['shipping_charge'] ?? 0 }},
            "total_quantity": {{ $data['orderDetails']->sum('qty') }},
            "items": [
                @foreach($data['orderDetails'] as $detail)
                {
                    "item_id": "{{ $detail->product_id }}",
                    "item_name": "{{ $detail->title }}",
                    "price": {{ $detail->price }},
                    "quantity": {{ $detail->qty }}
                },
                @endforeach
            ],
            "customer_info": {
                "first_name": "{{ $data['name'] }}",
                "phone": "{{ $data['phone'] }}"
            }
        }
    });
</script>
@endpush
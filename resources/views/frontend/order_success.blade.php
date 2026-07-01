@extends('layouts.frontend.app')

@push('meta')
    <meta name='description' content="Order confirmation" />
    <meta name="robots" content="noindex" />
@endpush

@section('title', 'অর্ডার সম্পন্ন হয়েছে')

@section('content')
    @php
        $purchaseEventId = \App\Support\Tracking::purchaseEventId($order);
        $currency = setting('CURRENCY_CODE_MIN') ?? 'TK';
        $waNumber = preg_replace('/[^0-9]/', '', setting('WHATSAPP_FLOAT_NUMBER') ?: (setting('whatsapp') ?? ''));
        $deliveryEstimate = setting('DELIVERY_ESTIMATE') ?: '২–৫ কর্মদিবসের মধ্যে';
        $waText = rawurlencode('আসসালামু আলাইকুম, আমার অর্ডার আইডি: ' . $order->order_id . ' — আমি অর্ডারটি সম্পর্কে জানতে চাই।');
    @endphp

    <style>
        #order-done {
            --od-accent: #f85606;
            --od-ink: #15171c;
            --od-muted: #6b7280;
            --od-line: #e7e8ec;
            background:
                radial-gradient(700px 300px at 50% -10%, #fff1e8 0, transparent 60%),
                #f6f7f9;
            font-family: "Hind Siliguri", -apple-system, "Segoe UI", system-ui, sans-serif;
            color: var(--od-ink);
            padding: 36px 14px 90px;
            min-height: 70vh;
        }
        #order-done * { box-sizing: border-box; }
        #order-done a { text-decoration: none; }
        .od-shell { max-width: 640px; margin: 0 auto; }
        .od-hero { text-align: center; margin-bottom: 22px; }
        .od-check {
            width: 84px; height: 84px; margin: 0 auto 16px; border-radius: 50%;
            background: linear-gradient(135deg, #16a34a, #15803d); color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 44px;
            box-shadow: 0 14px 34px rgba(22,163,74,.35); animation: od-pop .45s cubic-bezier(.2,.9,.3,1.4);
        }
        @keyframes od-pop { 0% { transform: scale(.3); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        .od-hero h1 { font-size: 1.6rem; font-weight: 800; margin: 0 0 6px; }
        .od-hero p { color: var(--od-muted); margin: 0; }
        .od-badge {
            display: inline-block; margin-top: 14px; background: #fff; border: 1px dashed var(--od-accent);
            color: var(--od-accent); font-weight: 800; padding: 8px 18px; border-radius: 999px; letter-spacing: .5px;
        }
        .od-card {
            background: #fff; border: 1px solid var(--od-line); border-radius: 18px; padding: 20px;
            box-shadow: 0 10px 30px rgba(17,23,28,.05); margin-bottom: 16px;
        }
        .od-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 14px; padding-bottom: 10px; border-bottom: 1px dashed var(--od-line); }
        .od-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f2f4; }
        .od-item:last-child { border-bottom: 0; }
        .od-item img { width: 50px; height: 58px; border-radius: 10px; object-fit: cover; background: #f1f2f4; }
        .od-item .od-it-info { flex: 1; min-width: 0; }
        .od-item .od-it-info strong { display: block; font-weight: 600; font-size: .9rem; }
        .od-item .od-it-info span { font-size: .78rem; color: var(--od-muted); }
        .od-item .od-it-price { font-weight: 700; white-space: nowrap; }
        .od-row { display: flex; justify-content: space-between; font-size: .92rem; color: var(--od-muted); margin-top: 10px; }
        .od-row.od-total { font-size: 1.18rem; font-weight: 800; color: var(--od-ink); border-top: 1px solid var(--od-line); padding-top: 12px; margin-top: 12px; }
        .od-row.od-total span:last-child { color: var(--od-accent); }
        .od-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .od-meta div span { display: block; font-size: .76rem; color: var(--od-muted); }
        .od-meta div strong { font-size: .95rem; }
        .od-delivery { background: #ecfdf3; border: 1px solid #c7f0d8; border-radius: 14px; padding: 14px 16px; color: #166534; font-weight: 600; display: flex; gap: 10px; align-items: center; }
        .od-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .od-btn { flex: 1 1 160px; text-align: center; padding: 15px 18px; border-radius: 50px; font-weight: 800; font-size: 1rem; transition: transform .2s, box-shadow .2s, opacity .2s; }
        .od-btn-wa { background: #25d366; color: #fff; box-shadow: 0 12px 26px rgba(37,211,102,.3); }
        .od-btn-wa:hover { transform: translateY(-2px); color: #fff; }
        .od-btn-ghost { background: #fff; color: var(--od-ink); border: 1.6px solid var(--od-line); }
        .od-btn-ghost:hover { border-color: var(--od-accent); color: var(--od-accent); }
        @media (max-width: 480px) { .od-meta { grid-template-columns: 1fr; } }
    </style>

    <section id="order-done">
        <div class="od-shell">
            <div class="od-hero">
                <div class="od-check">✓</div>
                <h1>অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h1>
                <p>{{ $order->first_name }}, আপনার অর্ডারের জন্য ধন্যবাদ। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</p>
                <div class="od-badge">অর্ডার আইডি: {{ $order->order_id }}</div>
            </div>

            <div class="od-card">
                <h3>🚚 ডেলিভারি তথ্য</h3>
                <div class="od-delivery">
                    <span style="font-size:22px">⏱️</span>
                    <span>আনুমানিক ডেলিভারি: {{ $deliveryEstimate }} আপনার পণ্য পৌঁছে যাবে।</span>
                </div>
                <div class="od-meta" style="margin-top:14px">
                    <div><span>নাম</span><strong>{{ $order->first_name }}</strong></div>
                    <div><span>মোবাইল</span><strong>{{ $order->phone }}</strong></div>
                    @if ($order->address)
                        <div style="grid-column:1/-1"><span>ঠিকানা</span><strong>{{ $order->address }}</strong></div>
                    @endif
                    <div><span>পেমেন্ট</span><strong>{{ $order->payment_method }}</strong></div>
                    <div><span>স্ট্যাটাস</span><strong>{{ $order->pay_staus ? 'পরিশোধিত' : 'প্রক্রিয়াধীন' }}</strong></div>
                </div>
            </div>

            <div class="od-card">
                <h3>🧾 অর্ডার সারাংশ</h3>
                @foreach ($order->orderDetails as $detail)
                    <div class="od-item">
                        <img src="{{ asset('uploads/product/' . optional($detail->product)->image) }}" alt="" onerror="this.style.visibility='hidden'">
                        <div class="od-it-info">
                            <strong>{{ $detail->title }}</strong>
                            <span>পরিমাণ: {{ $detail->qty }} × {{ number_format($detail->price, 0) }} {{ $currency }}</span>
                        </div>
                        <span class="od-it-price">{{ number_format($detail->total_price, 0) }} {{ $currency }}</span>
                    </div>
                @endforeach

                <div class="od-row"><span>সাবটোটাল</span><span>{{ number_format($order->subtotal, 2) }} {{ $currency }}</span></div>
                @if ($order->discount > 0)
                    <div class="od-row"><span>ডিসকাউন্ট</span><span>- {{ number_format($order->discount, 2) }} {{ $currency }}</span></div>
                @endif
                <div class="od-row"><span>শিপিং চার্জ</span><span>{{ number_format($order->shipping_charge, 2) }} {{ $currency }}</span></div>
                <div class="od-row od-total"><span>সর্বমোট</span><span>{{ number_format($order->total, 2) }} {{ $currency }}</span></div>
            </div>

            <div class="od-actions">
                @if ($waNumber)
                    <a class="od-btn od-btn-wa" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener">
                        💬 হোয়াটসঅ্যাপে যোগাযোগ
                    </a>
                @endif
                <a class="od-btn od-btn-ghost" href="{{ url('/') }}">🛍️ আরও কেনাকাটা করুন</a>
            </div>

            <p style="text-align:center;color:#9aa0aa;font-size:.82rem;margin-top:18px">
                💡 পরবর্তী যোগাযোগের জন্য অর্ডার আইডিটি সংরক্ষণ করুন।
            </p>
        </div>
    </section>
@endsection

@push('js')
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            "event": "purchase",
            "event_id": @json($purchaseEventId),
            "ecommerce": {
                "transaction_id": "{{ $order->invoice }}",
                "value": {{ (float) $order->total }},
                "currency": "BDT",
                "shipping": {{ (float) ($order->shipping_charge ?? 0) }},
                "total_quantity": {{ (int) $order->orderDetails->sum('qty') }},
                "items": [
                    @foreach ($order->orderDetails as $detail)
                        { "item_id": "{{ $detail->product_id }}", "item_name": @json($detail->title), "price": {{ (float) $detail->price }}, "quantity": {{ (int) $detail->qty }} },
                    @endforeach
                ],
                "customer_info": { "first_name": @json($order->first_name), "phone": @json($order->phone) }
            }
        });
    </script>
@endpush

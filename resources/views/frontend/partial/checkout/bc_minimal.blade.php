@extends('layouts.frontend.app')

@push('meta')
    <meta name='description' content="Buy now product" />
    <meta name='keywords' content="@foreach ($product->tags as $tag){{ $tag->name . ', ' }} @endforeach" />
@endpush

@section('title', 'অর্ডার করুন')

@section('content')
    @php
        if ($request->qty >= 6 && $product->whole_price > 0) {
            $sub_total = $product->whole_price * $request->qty;
        } else {
            $sub_total = $request->dynamic_price * $request->qty;
        }

        $attr = [];
        $attributes = DB::table('attributes')->get();
        foreach ($attributes as $attribute) {
            $attribute_prouct = DB::table('attribute_product')
                ->select('*')
                ->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')
                ->addselect('attribute_values.name as vName')
                ->addselect('attribute_product.id as vid')
                ->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')
                ->where('attribute_product.product_id', $product->id)
                ->where('attributes.id', $attribute->id)
                ->get();
            if ($attribute_prouct->count() > 0) {
                $slug = $attribute->slug;
                $attr[$slug] = $request->$slug;
            }
        }

        $currency = setting('CURRENCY_CODE_MIN') ?? 'TK';
    @endphp

    @include('frontend.partial.checkout._premium_styles')

    <section id="premium-checkout" x-data="checkout()" x-cloak>
        <div class="pc-shell">

            <div class="pc-head">
                <h1>নিরাপদ চেকআউট</h1>
                <p>আপনার অর্ডারটি কয়েক সেকেন্ডেই সম্পন্ন করুন</p>
            </div>

            <form action="{{ route('order.buy.store_minimal') }}" method="POST" @submit.prevent="submitOrder($event)" novalidate>
                @csrf
                <input type="hidden" name="country" value="{{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}">
                <input type="hidden" name="id" value="{{ $request->id }}">
                <input type="hidden" name="qty" value="{{ $request->qty }}">
                <input type="hidden" name="color" value="{{ $request->color }}">
                <input type="hidden" name="size" value="{{ $attr != '' ? json_encode($attr) : 'blank' }}">
                <input type="hidden" name="dynamic_prices" value="{{ $request->dynamic_price }}">
                <input type="hidden" name="stotal" value="{{ $sub_total }}">
                <input type="hidden" name="partial_paid" id="partial_paid" value="0">
                <input type="hidden" id="lead_store_url" value="{{ route('incomplete.lead.store') }}">
                @if (!empty($request->pr))
                    <input type="hidden" name="pr" value="{{ $request->pr }}">
                @endif

                <div class="pc-grid">
                    {{-- ============ LEFT: FORM ============ --}}
                    <div class="pc-col-form">

                        <div class="pc-alert-server" x-show="errorMsg" x-transition x-text="errorMsg"></div>
                        @if (session('error'))
                            <div class="pc-alert-server">{{ session('error') }}</div>
                        @endif

                        {{-- Step 1: Contact --}}
                        <div class="pc-card">
                            <div class="pc-card-label"><span class="pc-step">১</span> যোগাযোগের তথ্য</div>

                            <div class="pc-field">
                                <input id="first_name" name="first_name" type="text" class="pc-input" placeholder=" "
                                    autocomplete="name" x-model="name" value="{{ auth()->user()->name ?? '' }}" required>
                                <label for="first_name">আপনার নাম <span class="pc-req">*</span></label>
                                <small class="pc-err" x-show="errors.first_name" x-text="errors.first_name"></small>
                            </div>

                            <div class="pc-field" :class="{ 'is-valid': phoneValid, 'is-invalid': touched.phone && !phoneValid }">
                                <input id="phone" name="phone" type="tel" inputmode="numeric" class="pc-input"
                                    placeholder=" " autocomplete="tel" x-model="phone"
                                    @blur="touched.phone = true; lookupCustomer()"
                                    value="{{ auth()->user()->phone ?? '' }}" required>
                                <label for="phone">মোবাইল নম্বর <span class="pc-req">*</span></label>
                                <span class="pc-valid-tick" x-show="phoneValid" x-transition>✓</span>
                                <small class="pc-ok" x-show="phoneValid" x-transition>নম্বরটি সঠিক আছে</small>
                                <small class="pc-err" x-show="touched.phone && !phoneValid"
                                    x-text="errors.phone || 'সঠিক বাংলাদেশি মোবাইল নম্বর দিন (যেমনঃ 01XXXXXXXXX)।'"></small>
                            </div>

                            <p class="pc-returning" x-show="returning" x-transition>
                                স্বাগতম! 👋 আপনার আগের তথ্য বসিয়ে দেওয়া হয়েছে।
                            </p>
                        </div>

                        {{-- Step 2: Delivery --}}
                        <div class="pc-card">
                            <div class="pc-card-label"><span class="pc-step">২</span> ডেলিভারি ঠিকানা</div>

                            <div class="pc-field">
                                <textarea id="address" name="address" rows="3" class="pc-input pc-textarea"
                                    placeholder=" " x-model="address"></textarea>
                                <label for="address">সম্পূর্ণ ঠিকানা</label>
                                <small class="pc-err" x-show="errors.address" x-text="errors.address"></small>
                            </div>

                            <div class="pc-field" :class="{ 'pc-hidden': payment !== 'uddoktapay' }" id="email_wrap">
                                <input id="email" name="email" type="email" class="pc-input" placeholder=" "
                                    x-model="email" :required="payment === 'uddoktapay'">
                                <label for="email">ইমেইল ঠিকানা</label>
                            </div>

                            @if ($product->sheba == 1)
                                <div class="pc-field">
                                    <input type="date" name="meet" id="meet" class="pc-input" placeholder=" " required>
                                    <label for="meet">সার্ভিস গ্রহণের তারিখ</label>
                                </div>
                            @endif

                            <div class="pc-note">⚠️ শিপিং চার্জ প্রতি কেজি ঢাকার ভিতরে ১১০৳ (পরবর্তী প্রতি কেজি ১৫৳), ঢাকার বাহিরে ১৩০৳ (পরবর্তী প্রতি কেজি ২০৳) যোগ হবে।</div>
                        </div>

                        {{-- Step 3: Payment --}}
                        <div class="pc-card">
                            <div class="pc-card-label"><span class="pc-step">৩</span> পেমেন্ট পদ্ধতি</div>

                            <div class="pc-pay-grid">
                                @if (setting('g_cod') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'Cash on Delivery' }">
                                        <input type="radio" name="payment_method" value="Cash on Delivery" x-model="payment" checked>
                                        <img src="{{ asset('/') }}icon/delivery-man.png" alt="Cash on Delivery" onerror="this.style.display='none'">
                                        <span>ক্যাশ অন ডেলিভারি</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_uddok') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'uddoktapay' }">
                                        <input type="radio" name="payment_method" value="uddoktapay" x-model="payment">
                                        <img src="{{ asset('/') }}icon/uddoktapay.png" alt="UddoktaPay" onerror="this.style.display='none'">
                                        <span>অনলাইন পেমেন্ট</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_aamar') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'aamarpay' }">
                                        <input type="radio" name="payment_method" value="aamarpay" x-model="payment">
                                        <img src="{{ asset('/') }}icon/aamarpay_logo.png" alt="aamarPay" onerror="this.style.display='none'">
                                        <span>aamarPay</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_bkash') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'Bkash' }">
                                        <input type="radio" name="payment_method" value="Bkash" x-model="payment">
                                        <img src="{{ asset('/') }}icon/bkash.png" alt="bKash" onerror="this.style.display='none'">
                                        <span>বিকাশ</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_nagad') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'Nagad' }">
                                        <input type="radio" name="payment_method" value="Nagad" x-model="payment">
                                        <img src="{{ asset('/') }}icon/nagad.png" alt="Nagad" onerror="this.style.display='none'">
                                        <span>নগদ</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_rocket') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'Rocket' }">
                                        <input type="radio" name="payment_method" value="Rocket" x-model="payment">
                                        <img src="{{ asset('/') }}icon/rocket.png" alt="Rocket" onerror="this.style.display='none'">
                                        <span>রকেট</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                                @if (setting('g_bank') == 'true')
                                    <label class="pc-pay" :class="{ 'selected': payment === 'Bank' }">
                                        <input type="radio" name="payment_method" value="Bank" x-model="payment">
                                        <img src="{{ asset('/') }}icon/bank.png" alt="Bank" onerror="this.style.display='none'">
                                        <span>ব্যাংক</span>
                                        <i class="pc-pay-check">✓</i>
                                    </label>
                                @endif
                            </div>

                            <div class="pc-pay-hint" x-show="payHint" x-html="payHint" x-transition></div>

                            <template x-if="['Bkash','Nagad','Rocket'].includes(payment)">
                                <div class="pc-field-row">
                                    <div class="pc-field"><input type="text" name="mobile_number" class="pc-input" placeholder=" " required><label>যে নম্বর থেকে পাঠিয়েছেন</label></div>
                                    <div class="pc-field"><input type="text" name="transaction_id" class="pc-input" placeholder=" " required><label>ট্রানজেকশন আইডি</label></div>
                                </div>
                            </template>

                            <template x-if="payment === 'Bank'">
                                <div class="pc-field-row pc-bank">
                                    <div class="pc-field"><input type="text" name="bank_name" class="pc-input" placeholder=" " required><label>ব্যাংকের নাম</label></div>
                                    <div class="pc-field"><input type="text" name="account_number" class="pc-input" placeholder=" " required><label>একাউন্ট নম্বর</label></div>
                                    <div class="pc-field"><input type="text" name="holder_name" class="pc-input" placeholder=" " required><label>হোল্ডারের নাম</label></div>
                                    <div class="pc-field"><input type="text" name="branch" class="pc-input" placeholder=" " required><label>ব্রাঞ্চ</label></div>
                                    <div class="pc-field"><input type="text" name="routing" class="pc-input" placeholder=" " required><label>রাউটিং নম্বর</label></div>
                                </div>
                            </template>
                        </div>

                        {{-- Place order --}}
                        <div class="pc-cta-wrap">
                            @include('frontend.partial.checkout._premium_cta')
                        </div>
                    </div>

                    {{-- ============ RIGHT: ORDER SUMMARY ============ --}}
                    <aside class="pc-col-summary">
                        <div class="pc-summary">
                            <button type="button" class="pc-summary-head" @click="summaryOpen = !summaryOpen">
                                <span>🧾 অর্ডার সারাংশ</span>
                                <span class="pc-summary-total">
                                    <span id="total-mini">{{ number_format($sub_total, 0) }}</span> {{ $currency }}
                                    <i class="pc-chev" :class="{ 'open': summaryOpen }">⌄</i>
                                </span>
                            </button>

                            <div class="pc-summary-body" x-show="summaryOpen" x-transition>
                                <div class="pc-items">
                                    <div class="product pc-item">
                                        <img src="{{ asset('uploads/product/' . $product->image) }}" alt="{{ $product->title }}" onerror="this.style.visibility='hidden'">
                                        <div class="pc-item-info">
                                            <a href="{{ route('product.details', $product->slug) }}">{{ $product->title }}</a>
                                            <span class="pc-item-qty">পরিমাণ: {{ $request->qty }}</span>
                                        </div>
                                        <span class="pc-item-price" style="text-align: right">{{ number_format($sub_total, 0) }} {{ $currency }}</span>
                                    </div>
                                </div>

                                <div class="pc-coupon">
                                    <input type="text" id="coupon" class="pc-input" placeholder="কুপন কোড">
                                    <button type="button" id="apply-coupon" class="pc-coupon-btn">প্রয়োগ</button>
                                </div>

                                <div class="pc-totals">
                                    <div class="pc-trow"><span>সাবটোটাল</span><span><span id="sub-total">{{ $sub_total }}</span> {{ $currency }}</span></div>
                                    <div class="pc-trow"><span>কুপন <span class="coupon-name"></span></span><span>- <span id="coupon">{{ Session::has('coupon') ? number_format(Session::get('coupon')['discount'], 2, '.', ',') : '0.00' }}</span> {{ $currency }}</span></div>
                                    <div class="pc-trow pc-grand"><span>সর্বমোট</span><span><span id="total">{{ number_format($sub_total, 2, '.', ',') }}</span> {{ $currency }}</span></div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/') }}assets/frontend/js/city.js"></script>
    <script>
        function checkout() {
            return {
                name: @json(auth()->user()->name ?? ''),
                phone: @json(auth()->user()->phone ?? ''),
                address: '',
                email: '',
                payment: @js(setting('g_cod') == 'true' ? 'Cash on Delivery' : ''),
                submitting: false,
                summaryOpen: window.innerWidth >= 1024,
                touched: { phone: false },
                errors: {},
                errorMsg: '',
                returning: false,
                lastLookup: '',

                init() {
                    window.addEventListener('resize', () => { if (window.innerWidth >= 1024) this.summaryOpen = true; });
                },

                normalizePhone(raw) {
                    let d = (raw || '').replace(/\D+/g, '');
                    if (d.length === 13 && d.startsWith('88')) d = d.slice(2);
                    else if (d.length === 12 && d.startsWith('880')) d = '0' + d.slice(3);
                    else if (d.length === 10 && d[0] === '1') d = '0' + d;
                    return d;
                },
                get phoneNormalized() { return this.normalizePhone(this.phone); },
                get phoneValid() { return /^01[3-9]\d{8}$/.test(this.phoneNormalized); },

                get payHint() {
                    switch (this.payment) {
                        case 'Cash on Delivery': return 'পণ্য হাতে পেয়ে টাকা পরিশোধ করুন।';
                        case 'Bkash': return @js(setting('bkash') ?? '') + ' — এই নম্বরে টাকা পাঠিয়ে ট্রানজেকশন আইডি দিন।';
                        case 'Nagad': return @js(setting('nagad') ?? '') + ' — এই নম্বরে টাকা পাঠিয়ে ট্রানজেকশন আইডি দিন।';
                        case 'Rocket': return @js(setting('rocket') ?? '') + ' — এই নম্বরে টাকা পাঠিয়ে ট্রানজেকশন আইডি দিন।';
                        case 'Bank': return @js('Bank: ' . (setting('bank_name') ?? '') . ' | Branch: ' . (setting('branch_name') ?? '') . ' | A/C: ' . (setting('bank_account') ?? '') . ' | Routing: ' . (setting('routing') ?? ''));
                        case 'uddoktapay':
                        case 'aamarpay': return 'অর্ডার কনফার্ম করার পর অনলাইনে পেমেন্ট সম্পন্ন করুন।';
                        default: return '';
                    }
                },

                lookupCustomer() {
                    if (!this.phoneValid || this.phoneNormalized === this.lastLookup) return;
                    this.lastLookup = this.phoneNormalized;
                    fetch(`{{ route('checkout.lookup') }}?phone=${encodeURIComponent(this.phoneNormalized)}`, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(d => {
                            if (!d.found) return;
                            let filled = false;
                            if (d.name && !this.name) { this.name = d.name; filled = true; }
                            if (d.address && !this.address) { this.address = d.address; filled = true; }
                            if (filled) { this.returning = true; setTimeout(() => this.returning = false, 6000); }
                        })
                        .catch(() => {});
                },

                applyErrors(errs) {
                    this.errors = {};
                    for (const key in errs) this.errors[key] = errs[key][0];
                    if (this.errors.phone) this.touched.phone = true;
                    this.$nextTick(() => {
                        const el = this.$root.querySelector('.pc-err:not([style*="display: none"])');
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                },

                async submitOrder(e) {
                    this.touched.phone = true;
                    this.errorMsg = '';
                    if (!this.name.trim()) { this.errors = { ...this.errors, first_name: 'আপনার নাম লিখুন।' }; }
                    if (!this.phoneValid) {
                        this.$root.querySelector('#phone')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                    if (this.submitting) return;
                    this.submitting = true;

                    const form = e.target;
                    try {
                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: new FormData(form)
                        });
                        if (res.status === 422) {
                            const j = await res.json();
                            this.applyErrors(j.errors || {});
                            this.submitting = false;
                            return;
                        }
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const j = await res.json();
                        if (j.redirect) { window.location.href = j.redirect; return; }
                        this.submitting = false;
                    } catch (err) {
                        this.errorMsg = 'কিছু একটা সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন।';
                        this.submitting = false;
                    }
                }
            };
        }
    </script>

    {{-- Buy-now coupon --}}
    <script>
        $(document).ready(function() {
            function number_format(number, decimals, dec_point, thousands_sep) {
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    toFixedFix = function(n, prec) { var k = Math.pow(10, prec); return Math.round(n * k) / k; },
                    s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
                if (s[0].length > 3) s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                if ((s[1] || '').length < prec) { s[1] = s[1] || ''; s[1] += new Array(prec - s[1].length + 1).join('0'); }
                return s.join(dec);
            }

            function recompute() {
                let stotal = parseFloat("{!! $sub_total !!}") || 0;
                let coupon = parseFloat(($('span#coupon').text() || '0').replace(/,/g, '')) || 0;
                let total = Math.max(0, stotal - coupon);
                $('span#total').text(number_format(total, 2, '.', ','));
                $('#total-mini').text(number_format(total, 0, '.', ','));
            }
            recompute();

            $(document).on('click', '#apply-coupon', function(e) {
                e.preventDefault();
                let code = $('input#coupon').val();
                let id = "{!! $request->id !!}", qty = "{!! $request->qty !!}", dynamic_price = "{!! $request->dynamic_price !!}";
                if (!code) { $('input#coupon').addClass('is-invalid'); return; }
                $.ajax({
                    type: 'GET',
                    url: '/apply/coupon/buy-now/' + code + '/' + id + '/' + qty + '/' + dynamic_price,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.alert == 'success') {
                            $('span#coupon').text(number_format(response.discount, 2, '.', ','));
                            $('span.coupon-name').text('(' + code + ')');
                            $('input#coupon').val('');
                            recompute();
                        }
                    }
                });
            });
        });
    </script>

    {{-- GA4 begin_checkout + incomplete-lead tracking --}}
    <script>
        $(document).ready(function() {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                "event": "begin_checkout",
                "ecommerce": { "currency": "BDT", "value": {{ $sub_total }}, "items": [{ "item_id": "{{ $product->id }}", "item_name": @json($product->title), "price": {{ $request->dynamic_price }}, "quantity": {{ $request->qty }} }] }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('first_name');
            const phoneInput = document.getElementById('phone');
            const leadUrl = document.getElementById('lead_store_url')?.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!nameInput || !phoneInput || !leadUrl || !csrfToken) return;
            let typingTimer;
            function save() {
                if (!nameInput.value && !phoneInput.value) return;
                fetch(leadUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ name: nameInput.value, phone: phoneInput.value, page_type: 'buy_now' }) }).catch(() => {});
            }
            function onType() { clearTimeout(typingTimer); typingTimer = setTimeout(save, 900); }
            nameInput.addEventListener('keyup', onType);
            phoneInput.addEventListener('keyup', onType);
            window.addEventListener('beforeunload', save);
        });
    </script>
@endpush

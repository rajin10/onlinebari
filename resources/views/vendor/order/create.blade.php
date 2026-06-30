@extends('layouts.vendor.app')

@section('title', 'Order Now')

@push('css')
    <link rel="stylesheet" href="{{ asset('/') }}assets/frontend/css/toast.min.css">
@endpush

@section('content')
    {{-- Page header --}}
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Order Now</h1>
        <nav class="text-sm text-slate-500">
            <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
            <span class="mx-1">/</span>
            <span>Order Now</span>
        </nav>
    </div>

    {{-- Outer card (plain div: form straddles body+footer so x-ui.card slot model cannot be used) --}}
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        {{-- Card header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="font-medium text-slate-900">Order Now</h3>
            <x-ui.button variant="danger" :href="routeHelper('product')">
                <i class="fas fa-long-arrow-alt-left"></i>
                Back to List
            </x-ui.button>
        </div>

        <form action="{{ route('vendor.product.order.store') }}" method="POST">
            @csrf

            {{-- Card body --}}
            <div class="p-4 space-y-4">

                {{-- Billing Info card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3">
                        <h2 class="font-medium text-white">Billing Info</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="mb-4">
                                <x-ui.input name="first_name" label="First Name *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="last_name" label="Last Name *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="company" label="Company (optional)" type="text" />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="country" label="Country/Region *" type="text" />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="address" label="Street address *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="city" label="Town/City *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="district" label="District *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="postcode" label="Postcode / ZIP (optional)" type="text" />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="phone" label="Phone *" type="text" required />
                            </div>
                            <div class="mb-4">
                                <x-ui.input name="email" label="Email Address *" type="text" required />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Method card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3">
                        <h2 class="font-medium text-white">Shipping Method</h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Shipping Method</label>
                                <div class="flex items-center gap-4 mt-1">
                                    <label class="inline-flex items-center gap-1 text-sm text-slate-700">
                                        <input name="shipping_method" value="Free" type="radio"> Free
                                    </label>
                                    <label class="inline-flex items-center gap-1 text-sm text-slate-700">
                                        <input name="shipping_method" value="Prime" type="radio"> Prime
                                    </label>
                                </div>
                                @error('shipping_method')
                                    <small class="text-sm text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div>
                                <x-ui.select name="payment_method" label="Payment Method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="Bkash">Bkash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Rocket">Rocket</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                </x-ui.select>
                                @error('payment_method')
                                    <small class="text-sm text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2" id="payment-details"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Summary card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3">
                        <h2 class="font-medium text-white">Order Summary</h2>
                    </div>
                    <div class="p-4">

                        <div class="overflow-x-auto mb-4">
                            <x-ui.table>
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <img src="{{ asset('uploads/product/' . $product->image) }}"
                                                alt="Product Image" width="60px">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.show', $product->id) }}">{{ $product->title }}</a>
                                        </td>
                                        <td>{{ $product->discount_price }}</td>
                                        <td>
                                            @foreach ($product->colors as $color)
                                                <input type="radio" name="color" id="color"
                                                    value="{{ $color->name }}"> {{ $color->name }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($product->sizes as $size)
                                                <input type="radio" name="size" id="size"
                                                    value="{{ $size->name }}"> {{ $size->name }}
                                            @endforeach
                                        </td>
                                        <td>
                                            <input type="number" class="w-20 rounded-md border border-slate-300 px-2 py-1 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" name="qty"
                                                id="qty" value="1">
                                            <input type="hidden" name="id" id="id"
                                                value="{{ $product->id }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </x-ui.table>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="mb-4">
                                <label for="coupon" class="block text-sm font-medium text-slate-700 mb-1">Apply Coupon:</label>
                                <input type="text" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" id="coupon"
                                    placeholder="Enter coupon here">
                                <small class="text-sm text-danger"></small>
                                <div class="mt-2">
                                    <x-ui.button type="button" id="apply-coupon" variant="primary">
                                        <i class="fas fa-plus-circle"></i>
                                        Apply
                                    </x-ui.button>
                                </div>
                            </div>
                            <div>
                                <x-ui.table>
                                    <tbody>
                                        <tr>
                                            <th width="40%">Subtotal</th>
                                            <td id="subtotal">{{ $product->discount_price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Quantity</th>
                                            <td id="quantity">1</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping Charge</th>
                                            <td id="shipping_charge">0</td>
                                        </tr>
                                        <tr>
                                            <th>Coupon (<span
                                                    id="coupon_name">{{ Session::has('coupon') ? Session::get('coupon')['name'] : '' }}</span>)
                                            </th>
                                            <td id="coupon_charge">
                                                {{ Session::has('coupon') ? Session::get('coupon')['discount'] : '0' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td id="total">0</td>
                                        </tr>
                                    </tbody>
                                </x-ui.table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Card footer (inside the form) --}}
            <div class="border-t border-slate-200 px-4 py-3">
                <x-ui.button type="submit" variant="primary">
                    <i class="fas fa-plus-circle"></i>
                    Submit
                </x-ui.button>
            </div>
        </form>
    </div>

@endsection

@push('js')
    <script src="{{ asset('/') }}assets/frontend/js/toast.min.js"></script>

    <script>
        let quantity = parseInt($('td#quantity').text());
        let subtotal = parseInt($('td#subtotal').text());
        let coupon_charge = parseInt($('td#coupon_charge').text());
        let shipping_charge = parseInt($('td#shipping_charge').text());

        calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);


        function calculateAmount(subtotal, quantity, shipping_charge, coupon) {

            let total = (subtotal * quantity) + shipping_charge + coupon;
            $('td#total').text(total);
        }

        $(document).on('input', '#qty', function(e) {

            let quantity = $(this).val();
            let subtotal = parseInt($('td#subtotal').text());
            let coupon_charge = parseInt($('td#coupon_charge').text());
            let shipping_charge = parseInt($('td#shipping_charge').text());

            $('input#qty').val(quantity);
            $('td#quantity').text(quantity);
            calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);

        })

        // calculateAmount(subtotal, qty, shipping_charge, coupon);

        $(document).on('click', '#apply-coupon', function(e) {
            e.preventDefault();

            $('#coupon').removeClass('border-danger').addClass('border-slate-300');

            let code = $('input#coupon').val();
            let id = "{!! $product->id !!}";
            let subtotal = parseInt($('td#subtotal').text());
            let shipping_charge = parseInt($('td#shipping_charge').text());
            let quantity = parseInt($('td#quantity').text());

            if (code != '') {
                $.ajax({
                    type: 'GET',
                    url: '/vendor/apply/coupon/' + code + '/' + id,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);

                        if (response.alert == 'Success') {
                            calculateAmount(subtotal, quantity, shipping_charge, response.discount);

                            $('td#coupon_charge').text(response.discount);
                            $('span#coupon_name').text(code);
                            $('#coupon').val('');
                        }

                        $.toast({
                            heading: response.alert,
                            text: response.message,
                            icon: response.alert.toLowerCase(),
                            position: 'top-right',
                            stack: false
                        });
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            } else {
                $('#coupon').removeClass('border-slate-300').addClass('border-danger');
            }

        });

        $(document).on('change', '#payment_method', function(e) {
            let method = $(this).val();
            let html = '';
            if (method == 'Bkash' || method == 'Nagad' || method == 'Rocket') {
                html += '<div class="mb-4">'
                html += '<label for="mobile_number" class="block text-sm font-medium text-slate-700 mb-1">Mobile Number</label>'
                html +=
                    '<input required type="text" name="mobile_number" id="mobile_number" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter your mobile number"/>'
                html += '</div>'
                html += '<div class="mb-4">'
                html += '<label for="transaction_id" class="block text-sm font-medium text-slate-700 mb-1">Transaction ID</label>'
                html +=
                    '<input required type="text" name="transaction_id" id="transaction_id" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter transaction id"/>'
                html += '</div>'
            } else if (method == 'Bank') {
                html += '<div class="mb-4">'
                html += '<label for="bank_name" class="block text-sm font-medium text-slate-700 mb-1">Bank Name</label>'
                html +=
                    '<input required type="text" name="bank_name" id="bank_name" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter bank name"/>'
                html += '</div>'
                html += '<div class="mb-4">'
                html += '<label for="account_number" class="block text-sm font-medium text-slate-700 mb-1">Account Number</label>'
                html +=
                    '<input required type="text" name="account_number" id="account_number" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter account number"/>'
                html += '</div>'
                html += '<div class="mb-4">'
                html += '<label for="holder_name" class="block text-sm font-medium text-slate-700 mb-1">Holder Name</label>'
                html +=
                    '<input required type="text" name="holder_name" id="holder_name" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter holder name"/>'
                html += '</div>'
                html += '<div class="mb-4">'
                html += '<label for="branch" class="block text-sm font-medium text-slate-700 mb-1">Branch Name</label>'
                html +=
                    '<input required type="text" name="branch" id="branch" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter branch name"/>'
                html += '</div>'
                html += '<div class="mb-4">'
                html += '<label for="routing" class="block text-sm font-medium text-slate-700 mb-1">Routing Number</label>'
                html +=
                    '<input required type="text" name="routing" id="routing" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Enter routing number"/>'
                html += '</div>'
            }

            $('#payment-details').html(html);
        })

        $(document).on('click', 'input[name="shipping_method"]', function(e) {
            let shipping_charge = 0;

            if ($("input[name='shipping_method']:checked").val() == 'Prime') {
                let charge = "{!! setting('shipping_charge') !!}";
                shipping_charge += parseInt(charge);
            } else {
                shipping_charge += 0;
            }

            $('td#shipping_charge').text(shipping_charge);

            let subtotal = parseInt($('td#subtotal').text());
            let quantity = parseInt($('td#quantity').text());
            let coupon_charge = parseInt($('td#coupon_charge').text());

            calculateAmount(subtotal, quantity, shipping_charge, coupon_charge);
        });
    </script>
@endpush

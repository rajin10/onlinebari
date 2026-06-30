@extends('layouts.admin.app')

@section('title', 'Order Now')

@push('css')
    <link rel="stylesheet" href="{{ asset('/') }}assets/frontend/css/toast.min.css">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Order Now</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Order Now</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-slate-800">Order Now</span>
                    <x-ui.button variant="danger" :href="routeHelper('product')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </x-slot:header>

            <form action="{{ route('admin.product.order.store') }}" method="POST">
                @csrf

                <div class="space-y-4">

                    {{-- Billing Info --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3">
                            <h2 class="font-semibold text-white">Billing Info</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div class="mb-4">
                                    <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">
                                        First Name <sup style="color: red;"></sup>*
                                    </label>
                                    <input required name="first_name" id="first_name" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('first_name') border-danger @else border-slate-300 @enderror" />
                                    @error('email')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">
                                        Last Name <sup style="color: red;"></sup>*
                                    </label>
                                    <input required name="last_name" id="last_name" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('last_name') border-danger @else border-slate-300 @enderror" />
                                    @error('last_name')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="company" class="block text-sm font-medium text-slate-700 mb-1">
                                        Company (optional)
                                    </label>
                                    <input required name="company" id="company" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('company') border-danger @else border-slate-300 @enderror" />
                                    @error('email')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="country" class="block text-sm font-medium text-slate-700 mb-1">
                                        Country/Region <sup style="color: red;">*</sup>
                                    </label>
                                    <input name="country" id="country" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('country') border-danger @else border-slate-300 @enderror" />
                                    @error('country')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">
                                        Street address <sup style="color: red;">*</sup>
                                    </label>
                                    <input required name="address" id="address" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('address') border-danger @else border-slate-300 @enderror" />
                                    @error('address')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="city" class="block text-sm font-medium text-slate-700 mb-1">
                                        Town/City <sup style="color: red;">*</sup>
                                    </label>
                                    <input required name="city" id="city" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('city') border-danger @else border-slate-300 @enderror" />
                                    @error('city')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="district" class="block text-sm font-medium text-slate-700 mb-1">
                                        District <sup style="color: red;">*</sup>
                                    </label>
                                    <input required name="district" id="district" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('district') border-danger @else border-slate-300 @enderror" />
                                    @error('email')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="postcode" class="block text-sm font-medium text-slate-700 mb-1">
                                        Postcode / ZIP(optional)
                                    </label>
                                    <input required name="postcode" id="postcode" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('postcode') border-danger @else border-slate-300 @enderror" />
                                    @error('email')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">
                                        Phone <sup style="color: red;">*</sup>
                                    </label>
                                    <input required name="phone" id="phone" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('phone') border-danger @else border-slate-300 @enderror" />
                                    @error('phone')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                                        Email Address <sup style="color: red;">*</sup>
                                    </label>
                                    <input required name="email" id="email" type="text"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('email') border-danger @else border-slate-300 @enderror" />
                                    @error('email')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Shipping Method --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3">
                            <h2 class="font-semibold text-white">Shipping Method</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Shipping Method</label>
                                    <div class="flex items-center gap-4 mt-1">
                                        <label class="flex items-center gap-1 text-sm">
                                            <input name="shipping_method" value="Free" type="radio"> Free
                                        </label>
                                        <label class="flex items-center gap-1 text-sm">
                                            <input name="shipping_method" value="Prime" type="radio"> Prime
                                        </label>
                                    </div>
                                    @error('shipping_method')
                                        <small class="text-sm text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-1">
                                        Payment Method
                                    </label>
                                    <select required name="payment_method" id="payment_method"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('payment_method') border-danger @else border-slate-300 @enderror">
                                        <option value="">Select Payment Method</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cash on Delivery">Cash on Delivery</option>
                                    </select>
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

                    {{-- Order Summary --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3">
                            <h2 class="font-semibold text-white">Order Summary</h2>
                        </div>
                        <div class="p-4 space-y-4">

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
                                            <a href="{{ route('admin.product.show', $product->id) }}" class="text-primary hover:underline">
                                                {{ $product->title }}
                                            </a>
                                        </td>
                                        <td>{{ $product->regular_price }}</td>
                                        <td>
                                            @foreach ($product->colors as $color)
                                                <label class="inline-flex items-center gap-1 text-sm">
                                                    <input type="radio" name="color" id="color"
                                                        value="{{ $color->name }}"> {{ $color->name }}
                                                </label>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($product->sizes as $size)
                                                <label class="inline-flex items-center gap-1 text-sm">
                                                    <input type="radio" name="size" id="size"
                                                        value="{{ $size->name }}"> {{ $size->name }}
                                                </label>
                                            @endforeach
                                        </td>
                                        <td>
                                            <input type="number" name="qty"
                                                id="qty" value="1" style="width:80px"
                                                class="block rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                            <input type="hidden" name="id" id="id"
                                                value="{{ $product->id }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </x-ui.table>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div>
                                    <div class="mb-4">
                                        <label for="coupon" class="block text-sm font-medium text-slate-700 mb-1">
                                            Apply Coupon:
                                        </label>
                                        <input type="text" id="coupon" placeholder="Enter coupon here"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        <small class="text-sm text-danger"></small>
                                    </div>
                                    <x-ui.button type="button" id="apply-coupon" variant="primary">
                                        <i class="fas fa-plus-circle"></i>
                                        Apply
                                    </x-ui.button>
                                </div>

                                <div>
                                    <x-ui.table>
                                        <tbody>
                                            <tr>
                                                <th style="width:40%">Subtotal</th>
                                                <td id="subtotal">{{ $product->regular_price }}</td>
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
                                                <th>Coupon (<span id="coupon_name">{{ Session::has('coupon') ? Session::get('coupon')['name'] : '' }}</span>)</th>
                                                <td id="coupon_charge">{{ Session::has('coupon') ? Session::get('coupon')['discount'] : '0' }}</td>
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

                <div class="border-t border-slate-200 px-4 py-3 mt-4">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-plus-circle"></i>
                        Submit
                    </x-ui.button>
                </div>

            </form>
        </x-ui.card>
    </section>

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

            $('#coupon').removeClass('is-invalid');

            let code = $('input#coupon').val();
            let id = "{!! $product->id !!}";
            let subtotal = parseInt($('td#subtotal').text());
            let shipping_charge = parseInt($('td#shipping_charge').text());
            let quantity = parseInt($('td#quantity').text());

            if (code != '') {
                $.ajax({
                    type: 'GET',
                    url: '/admin/apply/coupon/' + code + '/' + id,
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
                $('#coupon').addClass('is-invalid');
            }

        });

        $(document).on('change', '#payment_method', function(e) {
            let method = $(this).val();
            let html = '';
            if (method == 'Bkash' || method == 'Nagad' || method == 'Rocket') {
                html += '<div class="form-group col-md-6">'
                html += '<label for="mobile_number">Mobile Number</label>'
                html +=
                    '<input required type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Enter your mobile number"/>'
                html += '</div>'
                html += '<div class="form-group col-md-6">'
                html += '<label for="transaction_id">Transaction ID</label>'
                html +=
                    '<input required type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction id"/>'
                html += '</div>'
            } else if (method == 'Bank') {
                html += '<div class="form-group col-md-6">'
                html += '<label for="bank_name">Bank Name</label>'
                html +=
                    '<input required type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter bank name"/>'
                html += '</div>'
                html += '<div class="form-group col-md-6">'
                html += '<label for="account_number">Account Number</label>'
                html +=
                    '<input required type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter account number"/>'
                html += '</div>'
                html += '<div class="form-group col-md-6">'
                html += '<label for="holder_name">Holder Name</label>'
                html +=
                    '<input required type="text" name="holder_name" id="holder_name" class="form-control" placeholder="Enter holder name"/>'
                html += '</div>'
                html += '<div class="form-group col-md-6">'
                html += '<label for="branch">Branch Name</label>'
                html +=
                    '<input required type="text" name="branch" id="branch" class="form-control" placeholder="Enter branch name"/>'
                html += '</div>'
                html += '<div class="form-group col-md-6">'
                html += '<label for="routing">Routing Number</label>'
                html +=
                    '<input required type="text" name="routing" id="routing" class="form-control" placeholder="Enter routing number"/>'
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

@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
    {{-- Page header --}}
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Setting &ndash; <small class="text-base font-normal text-slate-500">Shop</small></h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:content-['/'] before:mx-1">My Profile</li>
        </ol>
    </div>

    {{-- Outer wrapper card --}}
    <x-ui.card>
        <x-slot:header>Application Layout</x-slot:header>

        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                {{-- Inner form card --}}
                <x-ui.card>
                    <x-slot:header>Setting &ndash; Shop</x-slot:header>

                    <form id="shopSettingsForm" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="10">

                        {{-- Checkout section --}}
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-0 list-none p-0 m-0">
                            <li class="col-span-full bg-slate-100 px-2 py-2 mb-1 font-bold text-slate-700">Checkout</li>

                            <li class="mb-4">
                                <x-ui.select name="GUEST_CHECKOUT" label="Gust Checkout" id="GUEST_CHECKOUT">
                                    <option class="bg-info" value="{{ $GUEST_CHECKOUT->value }}">
                                        {{ $GUEST_CHECKOUT->value == 1 ? 'On' : 'Off' }}
                                    </option>
                                    <option value="1">On</option>
                                    <option value="0">Off</option>
                                </x-ui.select>
                            </li>

                            <li class="mb-4">
                                <x-ui.select name="CHECKOUT_TYPE" label="Checkout Type" id="CHECKOUT_TYPE">
                                    <option class="bg-info" value="{{ setting('CHECKOUT_TYPE') ?? 0 }}">
                                        {{ setting('CHECKOUT_TYPE') == 1 ? 'Complex' : 'Minimal' }}
                                    </option>
                                    <option value="1">Complex</option>
                                    <option value="0">Minimal</option>
                                </x-ui.select>
                            </li>

                            <li class="mb-4">
                                <x-ui.input name="phone_min_dgt" label="Phone Number Minimum Digit" type="number"
                                    :value="setting('phone_min_dgt') ?? 11" />
                            </li>

                            <li class="mb-4">
                                <x-ui.input name="phone_max_dgt" label="Phone Number Maximum Digit" type="number"
                                    :value="setting('phone_max_dgt') ?? 11" />
                            </li>
                        </ul>

                        {{-- Withdrawn section --}}
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-0 list-none p-0 m-0">
                            <li class="col-span-full bg-slate-100 px-2 py-2 mb-1 font-bold text-slate-700">Withdrawn</li>

                            <li class="mb-4">
                                <x-ui.input name="min_rec" label="Minimum Recharge" type="text"
                                    :value="setting('min_rec') ?? 50" />
                            </li>

                            <li class="mb-4">
                                <x-ui.input name="min_with" label="Minimum Withdraw" type="text"
                                    :value="setting('min_with') ?? 500" />
                            </li>
                        </ul>

                        {{-- Shipping section --}}
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-0 list-none p-0 m-0">
                            <li class="col-span-full bg-slate-100 px-2 py-2 mb-1 font-bold text-slate-700">Shipping</li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="COUNTRY_SERVE" class="block text-sm font-medium text-slate-700">
                                        Country of Serve <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="COUNTRY_SERVE" id="COUNTRY_SERVE"
                                        placeholder="Bangladesh"
                                        value="{{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="shipping_range_inside" class="block text-sm font-medium text-slate-700">
                                        Text - Shipping in Range <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        name="shipping_range_inside" id="shipping_range_inside"
                                        type="text" value="{{ setting('shipping_range_inside') ?? 'Dhaka' }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="CURRENCY_CODE" class="block text-sm font-medium text-slate-700">
                                        Currency Code <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="CURRENCY_CODE" id="CURRENCY_CODE"
                                        placeholder="Currency code"
                                        value="{{ setting('CURRENCY_CODE') ?? 'BDT' }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="CURRENCY_CODE_MIN" class="block text-sm font-medium text-slate-700">
                                        Currency Code Small <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="CURRENCY_CODE_MIN" id="CURRENCY_CODE_MIN"
                                        placeholder="Currency code"
                                        value="{{ setting('CURRENCY_CODE_MIN') ?? 'Tk' }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="CURRENCY_ICON" class="block text-sm font-medium text-slate-700">
                                        Currency Icon <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="CURRENCY_ICON" id="CURRENCY_ICON"
                                        placeholder="Currency icon"
                                        value="{{ setting('CURRENCY_ICON') ?? '৳' }}" required>
                                </div>
                            </li>
                        </ul>

                        {{-- Calculation section --}}
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-0 list-none p-0 m-0">
                            <li class="col-span-full bg-slate-100 px-2 py-2 mb-1 font-bold text-slate-700">Calculation</li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="shop_commission" class="block text-sm font-medium text-slate-700">
                                        Vendor Commission <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="number" name="shop_commission" id="shop_commission"
                                        placeholder="Bangladesh"
                                        value="{{ setting('shop_commission') ?? 0 }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <x-ui.select name="is_point" label="Point System Status" id="is_point">
                                    <option class="bg-info" value="{{ setting('is_point') ?? 0 }}">
                                        {{ setting('is_point') == 1 ? 'Active' : 'Deactive' }}
                                    </option>
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </x-ui.select>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="Default_Point" class="block text-sm font-medium text-slate-700">
                                        Default Point <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="Default_Point" id="Default_Point"
                                        placeholder="Bangladesh"
                                        value="{{ setting('Default_Point') ?? 0 }}" required>
                                </div>
                            </li>

                            <li class="mb-4">
                                <div class="space-y-1">
                                    <label for="Point_rate" class="block text-sm font-medium text-slate-700">
                                        Point Rate <span class="text-red-500">*</span>
                                    </label>
                                    <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        type="text" name="Point_rate" id="Point_rate"
                                        placeholder="Bangladesh"
                                        value="{{ setting('Point_rate') ?? 0 }}" required>
                                </div>
                            </li>
                        </ul>

                        <hr class="my-4 border-slate-200">

                        <div class="border-t border-slate-200 px-4 py-3">
                            <x-ui.button type="submit" variant="success">
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>
        </div>
    </x-ui.card>
@endsection

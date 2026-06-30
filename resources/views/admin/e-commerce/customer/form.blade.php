@extends('layouts.admin.app')

@section('title')
    @isset($customer)
        Edit Customer
    @else
        Add Customer
    @endisset
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($customer)
                    Edit Customer
                @else
                    Add Customer
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">
                    @isset($customer)
                        Edit Customer
                    @else
                        Add Customer
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
            @endforeach
        @endif

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <!-- Card header -->
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-semibold text-slate-800">
                    @isset($customer)
                        Edit customer
                    @else
                        Add New customer
                    @endisset
                </h3>
                <div class="flex items-center gap-2">
                    @isset($product)
                        <x-ui.button variant="info" :href="routeHelper('customer/' . $product->id)" size="sm">
                            <i class="fas fa-eye"></i>
                            Show
                        </x-ui.button>
                    @endisset
                    <x-ui.button variant="danger" :href="routeHelper('customer')" size="sm">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ isset($customer) ? routeHelper('customer/' . $customer->id) : routeHelper('customer') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($customer)
                    @method('PUT')
                @endisset

                <!-- Card body -->
                <div class="p-4">
                    @php
                        $number = '';
                        if (isset($customer)) {
                            if ($customer->phone != 'null') {
                                $number = $customer->phone;
                            }
                        }
                    @endphp

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <x-ui.input name="name" label="Name:*" type="text"
                                placeholder="Write customer name"
                                :value="$customer->name ?? old('name')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="username" label="Username (unique):*" type="text"
                                placeholder="Write customer username"
                                :value="$customer->username ?? old('username')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="email" label="Email:*" type="email"
                                placeholder="example@gmail.com"
                                :value="$customer->email ?? old('email')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="phone" label="Phone:*" type="text"
                                placeholder="Enter Your Phone Number"
                                :value="$number"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="country" label="Country:*" type="text"
                                placeholder="write customer country name"
                                :value="$customer->customer_info->country ?? old('country')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="city" label="City:*" type="text"
                                placeholder="write customer city name"
                                :value="$customer->customer_info->city ?? old('city')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="street" label="Street:*" type="text"
                                placeholder="write customer street"
                                :value="$customer->customer_info->street ?? old('street')"
                                required />
                        </div>
                        <div>
                            <x-ui.input name="post_code" label="Post Code:*" type="text"
                                placeholder="write customer post code"
                                :value="$customer->customer_info->post_code ?? old('post_code')"
                                required />
                        </div>

                        @isset($customer)
                        @else
                            <div>
                                <x-ui.input name="password" label="Password:*" type="password"
                                    placeholder="********"
                                    required />
                            </div>
                            <div>
                                <x-ui.input name="password_confirmation" label="Confirm Password:*" type="password"
                                    placeholder="********"
                                    required />
                            </div>
                        @endisset
                    </div>
                </div>

                <!-- Card footer -->
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button type="submit" variant="primary">
                        @isset($customer)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>
            </form>
        </div>
    </section>
@endsection

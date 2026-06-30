@extends('layouts.admin.app')

@section('title')
    @isset($customer)
        Edit Information
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
                    Edit Information
                @else
                    Add Customer
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($customer)
                        Edit Information
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
            <!-- Card Header -->
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
                        <x-ui.button variant="info" :href="routeHelper('customer/' . $product->id)">
                            <i class="fas fa-eye"></i>
                            Show
                        </x-ui.button>
                    @endisset
                    <x-ui.button variant="danger" :href="route('admin.staff.list')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            <form action="{{ isset($customer) ? routeHelper('customer/' . $customer->id) : routeHelper('customer') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($customer)
                    @method('PUT')
                @endisset

                <!-- Card Body -->
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="name" label="Name:" type="text"
                            :value="$customer->name ?? old('name')"
                            placeholder="Write customer name" required />

                        <x-ui.input name="username" label="Username (unique):" type="text"
                            :value="$customer->username ?? old('username')"
                            placeholder="Write customer username" required />

                        <x-ui.input name="email" label="Email:" type="email"
                            :value="$customer->email ?? old('email')"
                            placeholder="example@gmail.com" required />

                        <x-ui.input name="phone" label="Phone:" type="text"
                            :value="$customer->phone ?? old('phone')"
                            placeholder="write customer phone number" required />

                        @isset($customer)
                        @else
                            <x-ui.input name="password" label="Password:" type="password"
                                placeholder="********" required />

                            <x-ui.input name="password_confirmation" label="Confirm Password:" type="password"
                                placeholder="********" required />
                        @endisset
                    </div>
                </div>

                <!-- Card Footer -->
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
        <!-- /.card -->
    </section>
@endsection

@extends('layouts.admin.app')

@section('title')
    @isset($vendor)
        Edit Vendor
    @else
        Add Vendor
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush

@section('content')
    {{-- Content Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($vendor)
                    Edit Vendor
                @else
                    Add Vendor
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($vendor)
                        Edit Vendor
                    @else
                        Add Vendor
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
            @endforeach
        @endif

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            {{-- Card Header --}}
            <div class="border-b border-slate-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">
                        @isset($vendor)
                            Edit Vendor
                        @else
                            Add New Vendor
                        @endisset
                    </h3>
                    <div class="flex items-center gap-2">
                        @isset($vendor)
                            <x-ui.button variant="info" :href="routeHelper('vendor/' . $vendor->id)">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button variant="danger" :href="routeHelper('vendor')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>
            </div>

            <form action="{{ isset($vendor) ? routeHelper('vendor/' . $vendor->id) : routeHelper('vendor') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($vendor)
                    @method('PUT')
                @endisset

                {{-- Card Body --}}
                <div class="p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:*</label>
                        <input type="text" name="name" id="name" placeholder="Write name"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->name ?? old('name') }}" required>
                        @error('name')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-slate-700 mb-1">Username (unique):*</label>
                        <input type="text" name="username" id="username" placeholder="Write username"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('username') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->username ?? old('username') }}" required>
                        @error('username')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email:*</label>
                        <input type="email" name="email" id="email" placeholder="example@gmail.com"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('email') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->email ?? old('email') }}" required>
                        @error('email')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone:*</label>
                        <input type="text" name="phone" id="phone" placeholder="write phone number"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('phone') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->phone ?? old('phone') }}" required>
                        @error('phone')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Shop Name --}}
                    <div class="mb-4">
                        <label for="shop_name" class="block text-sm font-medium text-slate-700 mb-1">Shop Name:*</label>
                        <input type="text" name="shop_name" id="shop_name" placeholder="write shop name"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('shop_name') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->name ?? old('shop_name') }}" required>
                        @error('shop_name')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fb page Url --}}
                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-slate-700 mb-1">Fb page Url:</label>
                        <input type="text" name="url" id="url" placeholder="write shop url"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('url') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->url ?? old('url') }}">
                        @error('url')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Account --}}
                    <div class="mb-4">
                        <label for="bank_account" class="block text-sm font-medium text-slate-700 mb-1">Bank Account:*</label>
                        <input type="text" name="bank_account" id="bank_account" placeholder="write bank account"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('bank_account') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->bank_account ?? old('bank_account') }}" required>
                        @error('bank_account')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bank Name --}}
                    <div class="mb-4">
                        <label for="bank_name" class="block text-sm font-medium text-slate-700 mb-1">Bank Name:*</label>
                        <input type="text" name="bank_name" id="bank_name" placeholder="write bank name"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('bank_name') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->bank_name ?? old('bank_name') }}" required>
                        @error('bank_name')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Holder Name --}}
                    <div class="mb-4">
                        <label for="holder_name" class="block text-sm font-medium text-slate-700 mb-1">Holder Name:*</label>
                        <input type="text" name="holder_name" id="holder_name" placeholder="write holder name"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('holder_name') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->holder_name ?? old('holder_name') }}" required>
                        @error('holder_name')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Branch Name --}}
                    <div class="mb-4">
                        <label for="branch_name" class="block text-sm font-medium text-slate-700 mb-1">Branch Name:*</label>
                        <input type="text" name="branch_name" id="branch_name" placeholder="write bank branch name"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('branch_name') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->branch_name ?? old('branch_name') }}" required>
                        @error('branch_name')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Routing --}}
                    <div class="mb-4">
                        <label for="routing" class="block text-sm font-medium text-slate-700 mb-1">Routing:*</label>
                        <input type="text" name="routing" id="routing" placeholder="write routing"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('routing') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->routing ?? old('routing') }}" required>
                        @error('routing')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address:*</label>
                        <input type="text" name="address" id="address" placeholder="write shop address"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('address') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->address ?? old('address') }}" required>
                        @error('address')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commission --}}
                    <div class="mb-4">
                        <label for="commission" class="block text-sm font-medium text-slate-700 mb-1">Commission(Optional):</label>
                        <input type="number" name="commission" id="commission" placeholder="write commission"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('commission') border-danger @else border-slate-300 @enderror"
                            value="{{ $vendor->shop_info->commission ?? old('commission') }}">
                        @error('commission')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description (full width) --}}
                    <div class="mb-4 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description:*</label>
                        <textarea name="description" id="description" rows="4" placeholder="write shop description"
                            class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('description') border-danger @else border-slate-300 @enderror">{{ $vendor->shop_info->description ?? old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password fields (create only) --}}
                    @isset($vendor)
                    @else
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password:*</label>
                            <input type="password" name="password" id="password" placeholder="********"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('password') border-danger @else border-slate-300 @enderror"
                                required>
                            @error('password')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password-confirm" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password:*</label>
                            <input type="password" name="password_confirmation" id="password-confirm"
                                placeholder="********"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                required>
                        </div>
                    @endisset

                    {{-- Shop Profile (dropify) --}}
                    <div class="mb-4">
                        <label for="profile" class="block text-sm font-medium text-slate-700 mb-1">Shop Profile:</label>
                        <input type="file" name="profile" id="profile" accept="image/*"
                            class="dropify @error('profile') border-danger @enderror"
                            data-default-file="@isset($vendor)/uploads/shop/profile/{{ $vendor->shop_info->profile }}@endisset">
                        @error('profile')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Shop Cover Photo (dropify) --}}
                    <div class="mb-4">
                        <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Shop Cover Photo:</label>
                        <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                            class="dropify"
                            data-default-file="@isset($vendor)/uploads/shop/cover/{{ $vendor->shop_info->cover_photo }}@endisset">
                        @error('cover_photo')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NID Card (dropify) --}}
                    <div class="mb-4">
                        <label for="nid" class="block text-sm font-medium text-slate-700 mb-1">Nid Card:</label>
                        <input type="file" name="nid" id="nid" accept="image/*"
                            class="dropify"
                            data-default-file="@isset($vendor)/uploads/shop/nid/{{ $vendor->shop_info->nid }}@endisset">
                        @error('nid')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Trade License (dropify) --}}
                    <div class="mb-4">
                        <label for="trade" class="block text-sm font-medium text-slate-700 mb-1">Trade license:</label>
                        <input type="file" name="trade" id="trade" accept="image/*"
                            class="dropify"
                            data-default-file="@isset($vendor)/uploads/shop/trade/{{ $vendor->shop_info->trade }}@endisset">
                        @error('trade')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                </div>{{-- /.card-body --}}

                {{-- Card Footer --}}
                <div class="border-t border-slate-200 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <x-ui.button type="submit" variant="primary">
                            @isset($vendor)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </div>{{-- /.card-footer --}}
            </form>
        </div>{{-- /.card --}}
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush

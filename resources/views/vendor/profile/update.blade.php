@extends('layouts.vendor.app')

@section('title', 'Update Profile')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">My Profile</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
                <span class="mx-1">/</span>
                <span>My Profile</span>
            </nav>
        </div>
    </section>

    <section>

        <x-ui.card header="My Profile">

            <form action="{{ routeHelper('profile/info') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Images card --}}
                    <x-ui.card header="Images">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="mb-4">
                                <label for="avatar" class="block text-sm font-medium text-slate-700 mb-1">Profile Image</label>
                                <input type="file" name="avatar"
                                    id="avatar" class="dropify @error('avatar') is-invalid @enderror"
                                    data-default-file="{{ '/uploads/member/' . $vendor->avatar }}">
                                @error('avatar')
                                    <div class="block text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="profile" class="block text-sm font-medium text-slate-700 mb-1">Shop Profile</label>
                                <input type="file" name="profile"
                                    id="profile" class="dropify @error('profile') is-invalid @enderror"
                                    data-default-file="{{ '/uploads/shop/profile/' . $vendor->shop_info->profile }}">
                                @error('profile')
                                    <div class="block text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Shop Cover Photo</label>
                                <input type="file" name="cover_photo"
                                    id="cover_photo" class="dropify @error('cover_photo') is-invalid @enderror"
                                    data-default-file="{{ '/uploads/shop/cover/' . $vendor->shop_info->cover_photo }}">
                                @error('cover_photo')
                                    <div class="block text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="mb-4">
                                <label for="nid" class="block text-sm font-medium text-slate-700 mb-1">Nid Card:</label>
                                <input type="file" name="nid" id="nid" accept="image/*"
                                    class="dropify @error('nid') is-invalid @enderror"
                                    data-default-file="@isset($vendor)/uploads/shop/nid/{{ $vendor->shop_info->nid }}@enderror">
                                @error('nid')
                                    <div class="block text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="trade" class="block text-sm font-medium text-slate-700 mb-1">Trade license:</label>
                                <input type="file" name="trade" id="trade" accept="image/*"
                                    class="dropify @error('trade') is-invalid @enderror"
                                    data-default-file="@isset($vendor)/uploads/shop/trade/{{ $vendor->shop_info->trade }}@enderror">
                                @error('trade')
                                    <div class="block text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </x-ui.card>

                    {{-- Basic Information card --}}
                    <x-ui.card header="Basic Information">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <div class="mb-4">
                                <x-ui.input name="name" label="Name"
                                    value="{{ $vendor->name ?? old('name') }}"
                                    placeholder="Name" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="username" label="Username"
                                    value="{{ $vendor->username ?? old('username') }}"
                                    placeholder="Username" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="email" type="email" label="Email"
                                    value="{{ $vendor->email ?? old('email') }}"
                                    placeholder="example@gmail.com" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="phone" label="Phone"
                                    value="{{ $vendor->phone ?? old('phone') }}"
                                    placeholder="Phone Number" maxlength="25" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="shop_name" label="Shop Name:" id="shop_name"
                                    value="{{ $vendor->shop_info->name ?? old('shop_name') }}"
                                    placeholder="Write shop name" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="url" label="Fb Page or Website Url:" id="url"
                                    value="{{ $vendor->shop_info->url ?? old('url') }}"
                                    placeholder="write shop url" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="bank_account" label="Bank Account:" id="bank_account"
                                    value="{{ $vendor->shop_info->bank_account ?? old('bank_account') }}"
                                    placeholder="write bank account" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="bank_name" label="Bank Name:" id="bank_name"
                                    value="{{ $vendor->shop_info->bank_name ?? old('bank_name') }}"
                                    placeholder="write bank name" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="holder_name" label="Holder Name:" id="holder_name"
                                    value="{{ $vendor->shop_info->holder_name ?? old('holder_name') }}"
                                    placeholder="write holder name" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="branch_name" label="Branch Name:" id="branch_name"
                                    value="{{ $vendor->shop_info->branch_name ?? old('branch_name') }}"
                                    placeholder="write bank branch name" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="routing" label="Routing:" id="routing"
                                    value="{{ $vendor->shop_info->routing ?? old('routing') }}"
                                    placeholder="write routing" required />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="address" label="Address:" id="address"
                                    value="{{ $vendor->shop_info->address ?? old('address') }}"
                                    placeholder="write shop address" required />
                            </div>

                            <div class="mb-4 md:col-span-2">
                                <x-ui.textarea name="description" label="Description:" rows="4"
                                    id="description" placeholder="write shop description">{{ $vendor->shop_info->description ?? old('description') }}</x-ui.textarea>
                            </div>

                            <div class="md:col-span-2 text-center">
                                <h3 class="text-lg font-semibold text-slate-800"><b>Mobile Banking</b></h3>
                            </div>

                            <div class="md:col-span-2">
                                <hr class="border-slate-200">
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="Bkash" label="Bkash Number:" id="Bkash"
                                    value="{{ $vendor->shop_info->bkash ?? old('Bkash') }}"
                                    placeholder="Bkash Number" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="Nagad" label="Nagad Number:" id="Nagad"
                                    value="{{ $vendor->shop_info->nogod ?? old('Nagad') }}"
                                    placeholder="Nagad Number" />
                            </div>

                            <div class="mb-4">
                                <x-ui.input name="Rocket" label="Rocket Number:" id="Rocket"
                                    value="{{ $vendor->shop_info->rocket ?? old('Rocket') }}"
                                    placeholder="Rocket Number" />
                            </div>

                        </div>

                    </x-ui.card>

                </div>

                <div class="border-t border-slate-200 px-4 py-3 mt-4">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </div>

            </form>

        </x-ui.card>

    </section>

@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush

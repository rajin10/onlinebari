@extends('layouts.admin.app')

@section('title', 'Update Profile')

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

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">My Profile</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">My Profile</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <span class="text-base font-semibold text-slate-900">My Profile</span>
            </x-slot:header>

            @if (auth()->user()->desig == 1)
                <form action="{{ routeHelper('profile/info') }}" method="POST" enctype="multipart/form-data">
            @else
                <form action="{{ routeHelper('profile/info2') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    {{-- Images card --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3">
                            <h4 class="text-base font-semibold text-white">Images</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="mb-4">
                                    <label for="avatar" class="mb-1 block text-sm font-medium text-slate-700">Profile Image</label>
                                    <input type="file" name="avatar"
                                        id="avatar" class="dropify @error('avatar') is-invalid @enderror"
                                        data-default-file="{{ '/uploads/admin/' . $admin->avatar }}">
                                    @error('avatar')
                                        <div class="text-sm text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if (auth()->user()->desig == 1)
                                    <div class="mb-4">
                                        <label for="profile" class="mb-1 block text-sm font-medium text-slate-700">Shop Profile</label>
                                        <input type="file" name="profile"
                                            id="avatar" class="dropify @error('profile') is-invalid @enderror"
                                            data-default-file="{{ '/uploads/shop/profile/' . $admin->shop_info->profile }}">
                                        @error('profile')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Shop Cover Photo</label>
                                        <input type="file" name="cover_photo"
                                            id="cover_photo" class="dropify @error('cover_photo') is-invalid @enderror"
                                            data-default-file="{{ '/uploads/shop/cover/' . $admin->shop_info->cover_photo }}">
                                        @error('cover_photo')
                                            <div class="text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Basic Information card --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3">
                            <h4 class="text-base font-semibold text-white">Basic Information</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div class="mb-4">
                                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                                    <input type="text" name="name" id="name"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @enderror"
                                        value="{{ $admin->name ?? old('name') }}" placeholder="Name">
                                    @error('name')
                                        <p class="text-sm text-danger"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="username" class="mb-1 block text-sm font-medium text-slate-700">Username</label>
                                    <input type="text" name="username" id="username"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('username') border-danger @enderror"
                                        value="{{ $admin->username ?? old('username') }}" placeholder="Username">
                                    @error('username')
                                        <p class="text-sm text-danger"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('email') border-danger @enderror"
                                        value="{{ $admin->email ?? old('email') }}" placeholder="example@gmail.com">
                                    @error('email')
                                        <p class="text-sm text-danger"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                                    <input type="text" name="phone" id="phone"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('phone') border-danger @enderror"
                                        maxlength="25"
                                        value="{{ $admin->phone ?? old('phone') }}" placeholder="Phone Number">
                                    @error('phone')
                                        <p class="text-sm text-danger"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>

                                @if (auth()->user()->desig == 1)
                                    <div class="mb-4">
                                        <label for="shop_name" class="mb-1 block text-sm font-medium text-slate-700">Shop Name:</label>
                                        <input type="text" name="shop_name" id="shop_name"
                                            placeholder="Write shop name"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('shop_name') border-danger @enderror"
                                            value="{{ $admin->shop_info->name ?? old('shop_name') }}" required>
                                        @error('shop_name')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="url" class="mb-1 block text-sm font-medium text-slate-700">Shop Url:</label>
                                        <input type="text" name="url" id="url"
                                            placeholder="write shop url"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('url') border-danger @enderror"
                                            value="{{ $admin->shop_info->url ?? old('url') }}" required>
                                        @error('url')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="bank_account" class="mb-1 block text-sm font-medium text-slate-700">Bank Account:</label>
                                        <input type="text" name="bank_account" id="bank_account"
                                            placeholder="write bank account"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('bank_account') border-danger @enderror"
                                            value="{{ $admin->shop_info->bank_account ?? old('bank_account') }}" required>
                                        @error('bank_account')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="bank_name" class="mb-1 block text-sm font-medium text-slate-700">Bank Name:</label>
                                        <input type="text" name="bank_name" id="bank_name"
                                            placeholder="write bank name"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('bank_name') border-danger @enderror"
                                            value="{{ $admin->shop_info->bank_name ?? old('bank_name') }}" required>
                                        @error('bank_name')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="holder_name" class="mb-1 block text-sm font-medium text-slate-700">Holder Name:</label>
                                        <input type="text" name="holder_name" id="holder_name"
                                            placeholder="write holder name"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('holder_name') border-danger @enderror"
                                            value="{{ $admin->shop_info->holder_name ?? old('holder_name') }}" required>
                                        @error('holder_name')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="branch_name" class="mb-1 block text-sm font-medium text-slate-700">Branch Name:</label>
                                        <input type="text" name="branch_name" id="branch_name"
                                            placeholder="write bank branch name"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('branch_name') border-danger @enderror"
                                            value="{{ $admin->shop_info->branch_name ?? old('branch_name') }}" required>
                                        @error('branch_name')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="routing" class="mb-1 block text-sm font-medium text-slate-700">Routing:</label>
                                        <input type="text" name="routing" id="routing"
                                            placeholder="write routing"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('routing') border-danger @enderror"
                                            value="{{ $admin->shop_info->routing ?? old('routing') }}" required>
                                        @error('routing')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="address" class="mb-1 block text-sm font-medium text-slate-700">Address:</label>
                                        <input type="text" name="address" id="address"
                                            placeholder="write shop address"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('address') border-danger @enderror"
                                            value="{{ $admin->shop_info->address ?? old('address') }}" required>
                                        @error('address')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4 md:col-span-2">
                                        <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description:</label>
                                        <textarea name="description" id="description" rows="4"
                                            placeholder="write shop description"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('description') border-danger @enderror">{{ $admin->shop_info->description ?? old('description') }}</textarea>
                                        @error('description')
                                            <p class="text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>

            <x-slot:footer>
                <x-ui.button variant="primary" type="submit" class="mt-1">
                    <i class="fas fa-arrow-circle-up"></i>
                    Update
                </x-ui.button>
            </x-slot:footer>

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

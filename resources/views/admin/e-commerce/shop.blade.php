@extends('layouts.admin.app')

@section('title', 'Shop Details')

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

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Shop Details</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-slate-700">Shop Details</li>
            </ol>
        </div>
    </section>

    <section>
        <x-ui.card>
            <x-slot:header>Shop Details</x-slot:header>

            <form action="{{ routeHelper('shop/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div class="md:col-span-2 mb-2">
                        <x-ui.button variant="primary" :href="route('admin.setting.site_info')">
                            Update Shop Information <i class="fas fa-caret-right"></i>
                        </x-ui.button>
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="shop_name"
                            label="Shop Name:"
                            type="text"
                            :value="$shop_info->name ?? old('shop_name')"
                            placeholder="write shop name"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="url"
                            label="Shop Url:"
                            type="text"
                            :value="$shop_info->url ?? old('url')"
                            placeholder="write shop url"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="bank_account"
                            label="Bank Account:"
                            type="text"
                            :value="$shop_info->bank_account ?? old('bank_account')"
                            placeholder="write bank account"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="bank_name"
                            label="Bank Name:"
                            type="text"
                            :value="$shop_info->bank_name ?? old('bank_name')"
                            placeholder="write bank name"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="holder_name"
                            label="Holder Name:"
                            type="text"
                            :value="$shop_info->holder_name ?? old('holder_name')"
                            placeholder="write holder name"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="branch_name"
                            label="Branch Name:"
                            type="text"
                            :value="$shop_info->branch_name ?? old('branch_name')"
                            placeholder="write bank branch name"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="routing"
                            label="Routing:"
                            type="text"
                            :value="$shop_info->routing ?? old('routing')"
                            placeholder="write routing"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <x-ui.input
                            name="address"
                            label="Address:"
                            type="text"
                            :value="$shop_info->address ?? old('address')"
                            placeholder="write shop address"
                            required
                        />
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <x-ui.textarea
                            name="description"
                            label="Description:"
                            rows="4"
                            placeholder="write shop description"
                        >{{ $shop_info->description ?? old('description') }}</x-ui.textarea>
                    </div>

                    <div class="mb-4">
                        <label for="profile" class="block text-sm font-medium text-slate-700 mb-1">Profile:</label>
                        <input type="file" name="profile" id="profile" accept="image/*"
                            class="dropify @error('profile') is-invalid @enderror"
                            data-default-file="/uploads/shop/profile/{{ $shop_info->profile }}">
                        @error('profile')
                            <div class="mt-1 text-sm text-danger block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                        <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                            class="dropify @error('cover_photo') is-invalid @enderror"
                            data-default-file="/uploads/shop/cover/{{ $shop_info->cover_photo }}">
                        @error('cover_photo')
                            <div class="mt-1 text-sm text-danger block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <x-slot:footer>
                    <x-ui.button type="submit" variant="success">
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
        $(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush

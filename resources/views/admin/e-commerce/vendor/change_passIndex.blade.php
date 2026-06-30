@extends('layouts.admin.app')

@section('title')
    @isset($vendor)
        Change Password
    @else
        Add Vendor
    @endisset
@endsection

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($vendor)
                    Change Password
                @else
                    Add Vendor
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($vendor)
                        Change Password
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
            {{-- Card header --}}
            <div class="border-b border-slate-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-slate-900">
                        @isset($vendor)
                            Change Password
                        @else
                            Add New Vendor
                        @endisset
                    </h3>
                    <div class="flex items-center gap-2">
                        @isset($vendor)
                            <x-ui.button variant="info" :href="routeHelper('vendor/' . $vendor->id)" size="sm">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button variant="danger" :href="routeHelper('vendor')" size="sm">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.vendor.change_pass', ['id' => $vendor->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @isset($vendor)
                    @method('PUT')
                @endisset

                {{-- Card body --}}
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="text-sm text-slate-700">
                            Name: {{ $vendor->name ?? old('name') }}<br />
                            Username: {{ $vendor->username ?? old('username') }}<br />
                            Shop Name: {{ $vendor->shop_info->name ?? old('shop_name') }}<br />
                            E-mail {{ $vendor->email ?? old('email') }}<br />
                            Phone: {{ $vendor->phone ?? old('phone') }}<br />
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password:</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="********"
                                    required
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('password') border-danger @else border-slate-300 @enderror"
                                />
                                @error('password')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password-confirm" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password:</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password-confirm"
                                    placeholder="********"
                                    required
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </div>
            </form>
        </div>
    </section>
@endsection

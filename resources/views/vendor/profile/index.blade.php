@extends('layouts.vendor.app')

@section('title', 'Your Information')

@section('content')

    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">My Profile</h1>
        <nav class="text-sm text-slate-500">
            <a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a>
            <span class="mx-1">/</span>
            <span>My Profile</span>
        </nav>
    </div>

    <x-ui.card header="My Profile">

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">Your Information</div>

            <div class="p-4">
                <div class="user-profile">

                    <h4 class="mb-3 text-base font-semibold text-slate-800">Image information</h4>

                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <img src="{{ Auth::user()->avatar != 'default.png' ? '/uploads/member/' . Auth::user()->avatar : '/default/user.jpg' }}"
                                class="max-w-full" width="250" alt="User Image">
                        </div>
                        <div>
                            <img src="{{ '/uploads/shop/profile/' . Auth::user()->shop_info->profile }}"
                                class="max-w-full" width="250" alt="User Image">
                        </div>
                        <div>
                            <img src="{{ '/uploads/shop/cover/' . Auth::user()->shop_info->cover_photo }}"
                                class="max-w-full" width="250" alt="User Image">
                        </div>
                    </div>

                    <h4 class="mb-3 text-base font-semibold text-slate-800">Basic information</h4>

                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Username:</span>
                        <span>{{ Auth::user()->username }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Name:</span>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Phone:</span>
                        <span>{{ Auth::user()->phone }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Email:</span>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Status:</span>
                        @if (Auth::user()->is_approved == 1)
                            <x-ui.badge variant="success">Active</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger">Pending</x-ui.badge>
                        @endif
                    </div>

                    <h4 class="mb-3 text-base font-semibold text-slate-800">Shop information</h4>

                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Shop Name:</span>
                        <span>{{ Auth::user()->shop_info->name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Shop URL:</span>
                        <span>{{ Auth::user()->shop_info->url }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Bank Account:</span>
                        <span>{{ Auth::user()->shop_info->bank_account }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Bank Name:</span>
                        <span>{{ Auth::user()->shop_info->bank_name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Holder Name:</span>
                        <span>{{ Auth::user()->shop_info->holder_name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Branch Name:</span>
                        <span>{{ Auth::user()->shop_info->branch_name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block w-44 pb-2 text-sm text-slate-500">Routing:</span>
                        <span>{{ Auth::user()->shop_info->routing }}</span>
                    </div>

                </div>
            </div>

            <div class="border-t border-slate-200 px-4 py-3">
                <x-ui.button href="{{ routeHelper('profile/update') }}">
                    <i class="fas fa-arrow-circle-up"></i>
                    Update
                </x-ui.button>
            </div>
        </div>

    </x-ui.card>

@endsection

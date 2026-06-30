@extends('layouts.admin.app')

@section('title', 'Your Information')

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">My Profile</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">My Profile</li>
            </ol>
        </div>
    </section>

    <section>

        <x-ui.card>
            <x-slot:header>My Profile</x-slot:header>

            <x-ui.card>
                <x-slot:header>Your Information</x-slot:header>

                <div class="user-profile">

                    <h4 class="text-base font-semibold text-slate-800 mb-3">Image information</h4>

                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="user-photo">
                            <img src="{{ Auth::user()->avatar != 'default.png' ? '/uploads/admin/' . Auth::user()->avatar : '/default/user.jpg' }}"
                                class="max-w-full" width="250" alt="User Image">
                        </div>
                        @if (auth()->user()->desig == 1)
                            <div class="user-photo">
                                <img src="{{ '/uploads/shop/profile/' . Auth::user()->shop_info->profile }}"
                                    class="max-w-full" alt="User Image" width="250">
                            </div>
                            <div class="user-photo">
                                <img src="{{ '/uploads/shop/cover/' . Auth::user()->shop_info->cover_photo }}"
                                    class="max-w-full" alt="User Image" width="250">
                            </div>
                        @endif
                    </div>

                    <h4 class="text-base font-semibold text-slate-800 mb-3">Basic information</h4>

                    <div class="mb-1">
                        <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Username:</span>
                        <span>{{ Auth::user()->username }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Name:</span>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Phone:</span>
                        <span>{{ Auth::user()->phone }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Email:</span>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Status:</span>
                        @if (Auth::user()->status)
                            <x-ui.badge variant="success">Active</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger">Disable</x-ui.badge>
                        @endif
                    </div>

                    @if (auth()->user()->desig == 1)
                        <h4 class="text-base font-semibold text-slate-800 mt-4 mb-3">Shop information</h4>

                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Shop Name:</span>
                            <span>{{ Auth::user()->shop_info->name }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Shop URL:</span>
                            <span>{{ Auth::user()->shop_info->url }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Bank Account:</span>
                            <span>{{ Auth::user()->shop_info->bank_account }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Bank Name:</span>
                            <span>{{ Auth::user()->shop_info->bank_name }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Holder Name:</span>
                            <span>{{ Auth::user()->shop_info->holder_name }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Branch Name:</span>
                            <span>{{ Auth::user()->shop_info->branch_name }}</span>
                        </div>
                        <div class="mb-1">
                            <span class="inline-block pb-[9px] w-[170px] text-sm text-slate-500">Routing:</span>
                            <span>{{ Auth::user()->shop_info->routing }}</span>
                        </div>
                    @endif

                </div>

                <x-slot:footer>
                    <x-ui.button variant="primary" :href="routeHelper('profile/update')">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </x-slot:footer>

            </x-ui.card>

        </x-ui.card>

    </section>

@endsection

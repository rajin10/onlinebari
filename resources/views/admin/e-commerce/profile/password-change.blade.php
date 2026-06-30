@extends('layouts.admin.app')

@section('title', 'Change Password')

@push('css')
@endpush

@section('content')

    {{-- Content Header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Change Password</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Change Password</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>

        <div class="flex justify-center">
            <div class="w-full md:w-1/2">
                <x-ui.card>
                    <x-slot:header>Change Password</x-slot:header>

                    <form action="{{ routeHelper('profile/password/update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div class="mb-4">
                                <x-ui.input
                                    name="current_password"
                                    label="Current Password:"
                                    type="password"
                                    placeholder="Current Password"
                                />
                            </div>

                            <div class="mb-4">
                                <x-ui.input
                                    name="password"
                                    label="New Password:"
                                    type="password"
                                    placeholder="New Password"
                                />
                            </div>

                            <div class="mb-4">
                                <x-ui.input
                                    name="password_confirmation"
                                    label="Confirm Password:"
                                    type="password"
                                    placeholder="Confirm Password"
                                />
                            </div>

                            <div class="border-t border-slate-200 -mx-4 px-4 pt-3 mt-4">
                                <x-ui.button type="submit" variant="primary">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Change Password
                                </x-ui.button>
                            </div>

                        </div>

                    </form>

                </x-ui.card>
            </div>
        </div>

    </section>

@endsection

@push('js')
@endpush

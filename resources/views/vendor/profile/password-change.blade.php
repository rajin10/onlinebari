@extends('layouts.vendor.app')

@section('title', 'Change Password')

@push('css')
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Change Password</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a>
                <span class="mx-1">/</span>
                <span>Change Password</span>
            </nav>
        </div>
    </section>

    <section>

        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <x-ui.card header="Change Password">

                    <form action="{{ routeHelper('profile/password/update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <x-ui.input
                                type="password"
                                name="current_password"
                                label="Current Password:"
                                placeholder="Current Password"
                            />

                            <x-ui.input
                                type="password"
                                name="password"
                                label="New Password:"
                                placeholder="New Password"
                            />

                            <x-ui.input
                                type="password"
                                name="password_confirmation"
                                label="Confirm Password:"
                                placeholder="Confirm Password"
                            />
                        </div>

                        <x-slot:footer>
                            <x-ui.button type="submit" variant="primary">
                                <i class="fas fa-arrow-circle-up"></i>
                                Change Password
                            </x-ui.button>
                        </x-slot:footer>

                    </form>

                </x-ui.card>
            </div>
        </div>

    </section>

@endsection

@push('js')
@endpush

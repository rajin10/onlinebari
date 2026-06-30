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

    {{-- Page header --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-semibold text-slate-800">System &ndash; <small class="text-base font-normal text-slate-500">Update</small></h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a></li>
            <li>/</li>
            <li class="text-slate-700">System Update</li>
        </ol>
    </div>

    {{-- Main content --}}
    <x-ui.card header="Application Update">
        <div class="flex flex-col gap-6">

            {{-- License card --}}
            <div class="mx-auto w-full max-w-2xl">
                <x-ui.card header="Setting - License">
                    <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="14">

                        <x-ui.textarea name="license_ssh_key" label="License Key (*)" rows="4" placeholder="Enter Lincense Key..." required>{{ setting('license_ssh_key') ?? '' }}</x-ui.textarea>

                    <x-slot:footer>
                        <x-ui.button type="submit" variant="success">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        </x-ui.button>
                    </x-slot:footer>
                    </form>
                </x-ui.card>
            </div>

            {{-- System Update card --}}
            <div class="mx-auto w-full max-w-2xl">
                <x-ui.card header="Setting - System Update">
                    <div class="mb-3 flex flex-wrap items-center gap-3">
                        <x-ui.button variant="info" :href="route('admin.info')">PHP Info</x-ui.button>

                        @php
                            function isEnabled($func)
                            {
                                return is_callable($func) && false === stripos(ini_get('disable_functions'), $func);
                            }
                        @endphp
                        @if (!isEnabled('shell_exec'))
                            <small class="text-sm text-danger">You have to enable your hosting shell_exec() before the
                                update</small>
                        @endif
                    </div>

                    <form action="{{ route('admin.update') }}" method="POST">
                        @csrf

                        <x-ui.input name="password" label="Enter your password to update" type="password" placeholder="***" required />

                    <x-slot:footer>
                        <x-ui.button type="submit" variant="success">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        </x-ui.button>
                    </x-slot:footer>
                    </form>
                </x-ui.card>
            </div>

        </div>
    </x-ui.card>

@endsection

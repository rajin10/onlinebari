@extends('layouts.admin.app')
@section('title', 'Settings')

@section('content')
    {{-- Page Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Credintial</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">SMS | Mail | Login | Register Configureation</li>
            </ol>
        </div>
    </section>

    {{-- COURIER Config --}}
    <section>
        <x-ui.card header="COURIER Configuration">
            <div class="flex flex-col gap-6">

                {{-- STEEDFAST --}}
                <div class="mx-auto w-full max-w-2xl">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-success px-4 py-3 font-medium text-white">
                            STEEDFAST Courier configuration
                        </div>

                        <form id="sms_config" action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-4">
                                <input type="hidden" name="type" value="12">
                                <ul class="space-y-3">
                                    <li>
                                        <small class="text-red-500">Get api from: <a
                                                href="https://www.steadfast.com.bd/" target="_blank"
                                                rel="noopener noreferrer" class="underline hover:text-red-700">www.steadfast.com.bd</a></small>
                                    </li>
                                    <li>
                                        <label for="STEEDFAST_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">API V1 STATUS</label>
                                        <select name="STEEDFAST_STATUS" id="STEEDFAST_STATUS"
                                            class="m-2 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            @if (setting('STEEDFAST_STATUS') ?? 0 == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </li>
                                    <li>
                                        <label for="STEEDFAST_API_KEY" class="block text-sm font-medium text-slate-700 mb-1 capitalize">API KEY</label>
                                        <input type="text" name="STEEDFAST_API_KEY" id="STEEDFAST_API_KEY"
                                            value="{{ setting('STEEDFAST_API_KEY') ?? '' }}"
                                            class="mt-2 mr-2 rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    </li>
                                    <li>
                                        <label for="STEEDFAST_API_SECRET_KEY" class="block text-sm font-medium text-slate-700 mb-1 capitalize">API
                                            SECRET</label>
                                        <input type="text" name="STEEDFAST_API_SECRET_KEY"
                                            id="STEEDFAST_API_SECRET_KEY"
                                            value="{{ setting('STEEDFAST_API_SECRET_KEY') ?? '' }}"
                                            class="mt-2 mr-2 rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    </li>
                                </ul>
                            </div>

                            <div class="border-t border-slate-200 px-4 py-3">
                                <x-ui.button type="submit" variant="success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- BD Courier --}}
                <div class="mx-auto w-full max-w-2xl">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="rounded-t-lg bg-primary px-4 py-3 font-medium text-white">
                            BD Courier Configuration (Fraud Checker)
                        </div>

                        <form id="bdcourier_config" action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-4">
                                <input type="hidden" name="type" value="12">
                                <ul class="space-y-3">
                                    <li>
                                        <small class="text-red-500">Get api from: <a href="https://bdcourier.com/"
                                                target="_blank" rel="noopener noreferrer" class="underline hover:text-red-700">bdcourier.com</a></small>
                                    </li>
                                    <li>
                                        <label for="BDCOURIER_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">STATUS</label>
                                        <select name="BDCOURIER_STATUS" id="BDCOURIER_STATUS"
                                            class="m-2 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            @if (setting('BDCOURIER_STATUS') ?? 0 == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </li>
                                    <li>
                                        <label for="BDCOURIER_API_KEY" class="block text-sm font-medium text-slate-700 mb-1 capitalize">API KEY (Bearer
                                            Token)</label>
                                        <input type="text" name="BDCOURIER_API_KEY" id="BDCOURIER_API_KEY"
                                            value="{{ setting('BDCOURIER_API_KEY') ?? 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2' }}"
                                            class="mt-2 mr-2 w-full rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    </li>
                                </ul>
                            </div>

                            <div class="border-t border-slate-200 px-4 py-3">
                                <x-ui.button type="submit" variant="primary">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </x-ui.card>
    </section>
@endsection

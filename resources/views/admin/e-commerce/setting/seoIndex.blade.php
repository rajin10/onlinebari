@extends('layouts.admin.app')

@section('title', 'Settings')


@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Header</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">My Profile</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <x-ui.card :header="'Application Settings'">
            <div class="flex justify-center">
                <div class="w-full max-w-2xl">

                    {{-- Inner card: form wraps body + footer so we use raw divs to avoid slot conflict --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                            Setting - Custom Header Code
                        </div>
                        <form action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="13">
                            @method('PUT')

                            <div class="p-4 space-y-4">

                                <div>
                                    <label for="site_title" class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                        Site default title <span class="text-red-500">(*)</span>
                                    </label>
                                    <input
                                        name="site_title"
                                        id="site_title"
                                        type="text"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Lems - Multivendor E-Commerce Solution"
                                        value="{{ setting('site_title') }}"
                                        required
                                    />
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                        Meta description <span class="text-red-500">(*)</span>
                                    </label>
                                    <textarea
                                        name="meta_description"
                                        id="meta_description"
                                        rows="4"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Bangladeshi Best Ecommerce Software Lems, Optimized and super valuable extendable multivendor E-commerce solution"
                                        required
                                    >{{ setting('meta_description') }}</textarea>
                                </div>

                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                        Meta keywords <span class="text-red-500">(*)</span>
                                    </label>
                                    <textarea
                                        name="meta_keywords"
                                        id="meta_keywords"
                                        rows="4"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                        placeholder="Quality Cloths, Backpack bangladesh, BD price Ba"
                                        required
                                    >{{ setting('meta_keywords') }}</textarea>
                                </div>

                                <div>
                                    <a href="{{ route('admin.setting') }}#meta_img_wrap" class="text-primary hover:underline">
                                        Meta Image <i class="fas fa-caret-right"></i>
                                    </a>
                                </div>

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
            </div>
        </x-ui.card>
    </section>

@endsection

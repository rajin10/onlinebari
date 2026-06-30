@extends('layouts.admin.app')

@section('title')
    @isset($brand)
        Edit Brand
    @else
        Add Brand
    @endisset
@endsection

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
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($brand)
                    Edit Brand
                @else
                    Add Brand
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-slate-700">
                    @isset($brand)
                        Edit Brand
                    @else
                        Add Brand
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <div class="mx-auto max-w-3xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="font-semibold text-slate-800">
                        @isset($brand)
                            Edit Brand
                        @else
                            Add New Brand
                        @endisset
                    </h3>
                    <div class="flex items-center gap-2">
                        @isset($brand)
                            <x-ui.button variant="info" size="sm" :href="routeHelper('brand/' . $brand->id)">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button variant="danger" size="sm" :href="routeHelper('brand')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>

                {{-- Form spanning card body + footer --}}
                <form action="{{ isset($brand) ? routeHelper('brand/' . $brand->id) : routeHelper('brand') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($brand)
                        @method('PUT')
                    @endisset

                    {{-- Card body --}}
                    <div class="p-4 space-y-4">
                        {{-- Name --}}
                        <x-ui.input
                            name="name"
                            label="Name:"
                            type="text"
                            :value="$brand->name ?? old('name')"
                            placeholder="Write brand name"
                            required
                            autocomplete="off"
                        />

                        {{-- Description --}}
                        <x-ui.textarea name="description" label="Description:" :rows="5" placeholder="Write category description">{{ $brand->description ?? old('description') }}</x-ui.textarea>

                        {{-- Cover Photo --}}
                        <div>
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm @error('cover_photo') border-danger @enderror"
                                data-default-file="@isset($brand)/uploads/brand/{{ $brand->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="sr-only peer" name="status" id="status" ___inline_directive_________________________________________________________________________3___>
                                <div class="relative w-10 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button variant="primary" type="submit">
                            @isset($brand)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush

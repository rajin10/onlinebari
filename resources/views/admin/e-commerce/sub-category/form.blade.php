@extends('layouts.admin.app')

@section('title')
    @isset($subCategory)
        Edit Sub Category
    @else
        Add Sub Category
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
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($subCategory)
                    Edit Sub Category
                @else
                    Add Sub Category
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">
                    @isset($subCategory)
                        Edit Sub Category
                    @else
                        Add Sub Category
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <div class="mx-auto max-w-3xl">
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800">
                            @isset($subCategory)
                                Edit Sub Category Details
                            @else
                                Add New Sub Category
                            @endisset
                        </h3>
                        <x-ui.button variant="danger" :href="routeHelper('sub-category')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </x-slot:header>

                <form
                    action="{{ isset($subCategory) ? routeHelper('sub-category/' . $subCategory->id) : routeHelper('sub-category') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($subCategory)
                        @method('PUT')
                    @endisset

                    <div class="space-y-4">
                        {{-- Category select --}}
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Select Category:</label>
                            <select name="category" id="category"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('category') border-danger @else border-slate-300 @enderror">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @isset($subCategory) {{ $subCategory->category_id == $category->id ? 'selected' : '' }} @endisset>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Write sub category name"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $subCategory->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cover Photo (Dropify — keep raw input, JS hooks preserved) --}}
                        <div class="mb-4">
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-md @enderror"
                                data-default-file="@isset($subCategory) /uploads/sub category/{{ $subCategory->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" ___inline_directive_______________________________________________________________________________4___>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Is Feature toggle --}}
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="peer sr-only" name="is_feature" id="is_feature" ___inline_directive___________________________________________________________________________________5___>
                                <label for="is_feature"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm text-slate-700">is_features</span>
                            </div>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <x-slot:footer>
                        <div class="flex items-center">
                            <x-ui.button type="submit" variant="primary">
                                @isset($subCategory)
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                @else
                                    <i class="fas fa-plus-circle"></i>
                                    Submit
                                @endisset
                            </x-ui.button>
                        </div>
                    </x-slot:footer>
                </form>
            </x-ui.card>
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

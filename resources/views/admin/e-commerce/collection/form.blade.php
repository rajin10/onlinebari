@extends('layouts.admin.app')

@section('title')
    @isset($collection)
        Edit Collection
    @else
        Add Collection
    @endisset
@endsection

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
                @isset($collection)
                    Edit Collection
                @else
                    Add Collection
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">
                    @isset($collection)
                        Edit Collection
                    @else
                        Add Collection
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">
        <div class="mx-auto max-w-3xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="text-base font-semibold text-slate-800">
                        @isset($collection)
                            Edit Collection Details
                        @else
                            Add New Collection
                        @endisset
                    </h3>
                    <div class="flex items-center gap-2">
                        @isset($collection)
                            <x-ui.button variant="info" :href="routeHelper('collection/' . $collection->id)">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button variant="danger" :href="routeHelper('collection')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>

                {{-- Form wraps body + footer --}}
                <form
                    action="{{ isset($collection) ? routeHelper('collection/' . $collection->id) : routeHelper('collection') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($collection)
                        @method('PUT')
                    @endisset

                    {{-- Card body --}}
                    <div class="p-4 space-y-4">

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Write category name"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $collection->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categories --}}
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Select Category:</label>
                            <select name="categories[]" id="category" multiple data-placeholder="Select Category"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary select2 @error('categories') border-danger @else border-slate-300 @enderror"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @isset($collection) @foreach ($collection->categories as $pro_category) {{ $category->id == $pro_category->id ? 'selected' : '' }} @endforeach @endisset>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categories')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cover Photo (dropify JS widget — keep raw input, only restyle wrapper) --}}
                        <div class="mb-4">
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm @error('cover_photo') border-danger @enderror"
                                data-default-file="@isset($collection) /uploads/collection/{{ $collection->cover_photo }}@enderror">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div class="mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status" id="status" ___inline_directive______________________________________________________________________________4___>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            @isset($collection)
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
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
            $('.select2').select2();
        });
    </script>
@endpush

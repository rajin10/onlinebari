@extends('layouts.admin.app')

@section('title')
    @isset($category)
        Edit Category
    @else
        Add Category
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
                @isset($category)
                    Edit Category
                @else
                    Add Category
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mr-1 before:content-['/']">
                    @isset($category)
                        Edit Category
                    @else
                        Add Category
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
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                    <h3 class="font-medium text-slate-900">Edit Category Details</h3>
                    <div class="flex items-center gap-2">
                        @isset($category)
                            <x-ui.button variant="info" :href="routeHelper('category/' . $category->id)">
                                <i class="fas fa-eye"></i>
                                Show
                            </x-ui.button>
                        @endisset
                        <x-ui.button variant="danger" :href="routeHelper('category')">
                            <i class="fas fa-long-arrow-alt-left"></i>
                            Back to List
                        </x-ui.button>
                    </div>
                </div>

                @if (!empty(Session::get('massage2')))
                    <div class="mx-4 mt-4 rounded-md border border-success/30 bg-success/10 px-4 py-2 text-center text-sm text-success">
                        {{ Session::get('massage2') }}
                    </div>
                @endif

                <form action="{{ route('admin.edit.extra') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="{{ $extra->id }}" name="ddddd">
                    @csrf

                    {{-- Card body --}}
                    <div class="p-4 space-y-4">

                        <div class="mb-4">
                            <label for="mainCategory" class="block text-sm font-medium text-slate-700 mb-1">Category name:</label>
                            <select name="main" id="mainCategory" class="category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option {{ $hascategories->id == $category->id ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="nsubc" class="block text-sm font-medium text-slate-700 mb-1">Select Sub Category:</label>
                            <select name="nsubc" id="nsubc" class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="{{ $hsasub->id }}">{{ $hsasub->name }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="mini" class="block text-sm font-medium text-slate-700 mb-1">Select mini Category:</label>
                            <select name="mini" id="mini" class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="{{ $hsaMini->id }}">{{ $hsaMini->name }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-ui.input
                                name="name"
                                label="Name:"
                                type="text"
                                :value="$extra->name ?? old('name')"
                                placeholder="Write category name"
                                required
                                autocomplete="off"
                            />
                        </div>

                        <div class="mb-4">
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full text-sm text-slate-700 @error('cover_photo') border border-danger rounded-md @enderror"
                                data-default-file="@isset($extra)/uploads/extra-category/{{ $extra->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status" id="status" {{ $extra->status ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="is_feature" id="is_feature"
                                    {{ $extra->is_feature ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">is_features</span>
                            </label>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            @isset($category)
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
    <script src="/assets/dist/extra.js"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush

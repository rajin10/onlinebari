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
    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($category)
                    Edit Category
                @else
                    Add Category
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">
                    @isset($category)
                        Edit Category
                    @else
                        Add Category
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <!-- Card header -->
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                        <span>
                            @isset($category)
                                Edit Category Details
                            @else
                                Add New Category
                            @endisset
                        </span>
                        <div class="flex items-center gap-2">
                            @isset($category)
                                <x-ui.button variant="info" size="sm" :href="routeHelper('category/' . $category->id)">
                                    <i class="fas fa-eye"></i>
                                    Show
                                </x-ui.button>
                            @endisset

                            <x-ui.button variant="danger" size="sm" :href="routeHelper('category')">
                                <i class="fas fa-long-arrow-alt-left"></i>
                                Back to List
                            </x-ui.button>
                        </div>
                    </div>

                    @if (!empty(Session::get('massage2')))
                        <div class="px-4 pt-3 text-center text-success">
                            {{ Session::get('massage2') }}
                        </div>
                    @endif

                    @isset($mini)
                        <form action="{{ route('admin.edit.mini') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $mini->id }}" name="ddddd">
                        @else
                            <form action="{{ route('admin.create.mini') }}" method="POST" enctype="multipart/form-data">
                            @endisset
                            @csrf

                            <!-- Card body -->
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Select Sub Category:</label>
                                    <select name="category" id="category"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('category') border-danger @else border-slate-300 @enderror">
                                        <option value="">Select Category</option>
                                        @foreach ($sub_categories as $category)
                                            <option value="{{ $category->id }}"
                                                @isset($mini) {{ $mini->category_id == $category->id ? 'selected' : '' }} @endisset>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name:</label>
                                    <input type="text" name="name" id="name" placeholder="Write category name"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                        value="{{ $mini->name ?? old('name') }}" required autocomplete="off">
                                    @error('name')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo:</label>
                                    <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                        class="block w-full @error('cover_photo') border border-danger rounded-md @enderror"
                                        data-default-file="@isset($mini)/uploads/mini-category/{{ $mini->cover_photo }}@endisset">
                                    @error('cover_photo')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <input type="checkbox" name="status" id="status"
                                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" ___inline_directive________________________________________________________________________4___>
                                        <span class="text-sm font-medium text-slate-700">Status</span>
                                    </label>
                                    @error('status')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <input type="checkbox" name="is_feature" id="is_feature"
                                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" ___inline_directive_____________________________________________________________________________5___>
                                        <span class="text-sm font-medium text-slate-700">is_features</span>
                                    </label>
                                    @error('is_feature')
                                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Card footer -->
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

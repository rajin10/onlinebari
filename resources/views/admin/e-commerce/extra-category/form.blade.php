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
                <li class="before:content-['/'] before:mx-1">
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
        <div class="mx-auto w-full md:w-2/3">
            <!-- Default box -->
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium text-slate-900">
                            @isset($category)
                                Edit Category Details
                            @else
                                Add New Category
                            @endisset
                        </h3>
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
                </div>
                @if (!empty(Session::get('massage2')))
                    <span class="mb-5 block rounded bg-white p-[5px] text-center shadow-sm text-[#1cc88a]">
                        {{ Session::get('massage2') }}</span>
                @endif


                    @isset($mini)
                        <form action="{{ route('admin.edit.mini') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" value="{{ $mini->id }}" name="ddddd">
                        @else
                            <form action="{{ route('admin.create.extra') }}" method="POST" enctype="multipart/form-data">
                            @endisset
                            @csrf
                            <div class="p-4">

                                <div class="mb-4">
                                    <label for="mainCategory" class="block text-sm font-medium text-slate-700 mb-1">Category name:</label>
                                    <select name="main" id="mainCategory" class="category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="nsubc" class="block text-sm font-medium text-slate-700 mb-1">Select Sub Category:</label>
                                    <select name="nsubc" id="nsubc" class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">

                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="mini" class="block text-sm font-medium text-slate-700 mb-1">Select mini Category:</label>
                                    <select name="mini" id="mini" class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">

                                    </select>
                                </div>


                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                                    <input type="text" name="name" id="name" placeholder="Write category name"
                                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                        value="{{ $mini->name ?? old('name') }}" required autocomplete="off">
                                    @error('name')
                                        <p class="text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                                    <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm @error('cover_photo') border-danger @enderror" data-default-file="@isset($mini) /uploads/mini-category/{{ $mini->cover_photo }}@enderror">
                            @error('cover_photo')
                                <p class="block text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status" id="status" ___inline_directive________________________________________________________________________2___>
                                <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                            </div>
                            @error('status')
                            <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="is_feature" id="is_feature" ___inline_directive____________________________________________________________________________3___>
                                <label class="text-sm font-medium text-slate-700" for="is_feature">is_features</label>
                            </div>
                            @error('is_feature')
                            <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="border-t border-slate-200 px-4 py-3">
                        <div class="mb-4">
                            <x-ui.button type="submit" variant="primary" class="mt-1">
                                @isset($category)
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                @else
                                    <i class="fas fa-plus-circle"></i>
                                    Submit
                                @endisset
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
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

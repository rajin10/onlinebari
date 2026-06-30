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
        <div class="w-full md:w-2/3 md:mx-auto">
            <!-- Card -->
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <!-- Card Header -->
                <div class="border-b border-slate-200 px-4 py-3">
                    <p class="mb-2 text-sm text-slate-500">
                        Already Taken Position:
                        @foreach (APP\Models\Category::all() as $pos)
                            {{ $pos->pos }},
                        @endforeach
                    </p>
                    <div class="flex flex-wrap items-center justify-between gap-2">
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

                <form
                    action="{{ isset($category) ? routeHelper('category/' . $category->id) : routeHelper('category') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($category)
                        @method('PUT')
                    @endisset

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Write category name"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $category->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Position:</label>
                            <input type="text" name="pos" id="pos" placeholder="Position"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $category->pos ?? old('pos') }}" autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description:</label>
                            <textarea name="description" id="description" cols="5" placeholder="Write category description"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('description') border-danger @else border-slate-300 @enderror">{{ $category->description ?? old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Photo (Dropify — keep raw input, JS hooks preserved) -->
                        <div class="mb-4">
                            <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-md @enderror"
                                data-default-file="@isset($category) /uploads/category/{{ $category->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status toggle -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" ___inline_directive____________________________________________________________________________4___>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Feature toggle -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="peer sr-only" name="is_feature" id="is_feature" ___inline_directive________________________________________________________________________________5___>
                                <label for="is_feature"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm text-slate-700">is_features</span>
                            </div>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Show on homepage toggle -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="peer sr-only" name="is_shown_on_homepage" id="is_shown_on_homepage" ___inline_directive__________________________________________________________________________________________6___>
                                <label for="is_shown_on_homepage"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm text-slate-700">Show on homepage</span>
                            </div>
                            @error('is_shown_on_homepage')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Card Footer -->
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
            <!-- /.card -->
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

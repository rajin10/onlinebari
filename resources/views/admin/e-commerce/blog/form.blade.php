@extends('layouts.admin.app')

@section('title')
    @isset($blog)
        Edit blog
    @else
        Add blog
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($blog)
                    Edit blog
                @else
                    Add blog
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($blog)
                        Edit blog
                    @else
                        Add blog
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <x-ui.card>
            <x-slot:header>
                @isset($blog)
                    Edit blog Details
                @else
                    Add New Campaign
                @endisset
            </x-slot:header>

            <form action="{{ isset($blog) ? route('admin.update_exit_blog') : route('admin.create_blog') }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @if (isset($blog))
                    <input type="hidden" value="{{ $blog->id }}" name="power" id="">
                @endif

                {{-- Title --}}
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title:</label>
                    <input type="text" name="title" id="title" placeholder="Write blog Title"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @enderror"
                        value="{{ $blog->title ?? old('name') }}" required autocomplete="off">
                    @error('name')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Thumbnail --}}
                <div class="mb-4">
                    <label for="thumbnail" class="block text-sm font-medium text-slate-700 mb-1">Thumbnail:</label>
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm @error('thumbnail') border-danger @enderror"
                        data-default-file="@isset($blog) {{ asset('/') }}/uploads/blogs/{{ $blog->thumbnail }}@enderror">
                    @error('thumbnail')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <x-ui.select name="category" label="Category">
                    <option>Select One</option>
                    @foreach ($categories as $category)
                        <option ___inline_directive_________________________________________________________________________2___ value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-ui.select>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="descripiton" class="block text-sm font-medium text-slate-700 mb-1">Description:</label>
                    <textarea name="descripiton" id="descripiton" rows="5"
                        placeholder="Write product short description"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary ___inline_directive___________________3___"
                        required>{{ $blog->description ?? old('descripiton') }}</textarea>
                    @error('descripiton')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status toggle --}}
                <div class="mb-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="custom-control-input" name="status" id="status" ___inline_directive________________________________________________________________________4___>
                        <span class="text-sm font-medium text-slate-700">Status</span>
                    </label>
                    @error('status')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-200 px-4 py-3 -mx-4 -mb-4 mt-4">
                    <x-ui.button type="submit" variant="primary">
                        @isset($blog)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>

            </form>
        </x-ui.card>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('#thumbnail').dropify();
            $('#descripiton').summernote();
        });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title')
    @isset($slider)
        Edit Slider
    @else
        Add Slider
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
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($slider)
                Edit Slider
            @else
                Add Slider
            @endisset
        </h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mx-1 before:content-['/']">
                @isset($slider)
                    Edit Slider
                @else
                    Add Slider
                @endisset
            </li>
        </ol>
    </div>

    {{-- Main content --}}
    <div class="flex justify-center">
        <div class="w-full max-w-2xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="font-semibold text-slate-800">
                        @isset($slider)
                            Edit Slider
                        @else
                            Add New Slider
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('slider')" size="sm">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>

                {{-- Form wraps both body and footer so the submit button is inside the form --}}
                <form action="{{ isset($slider) ? routeHelper('slider/' . $slider->id) : routeHelper('slider') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($slider)
                        @method('PUT')
                    @endisset

                    <div class="p-4">

                        {{-- Image upload --}}
                        <div class="mb-4">
                            <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Slider Image:</label>
                            <input type="file" name="image" id="image"
                                class="@error('image') border-danger @enderror"
                                @isset($slider) data-default-file="{{ '/uploads/slider/' . $slider->image }}" @endisset>
                            @error('image')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- URL --}}
                        <div class="mb-4">
                            <x-ui.input name="url" label="Slider Url:" type="text"
                                placeholder="Write slider url"
                                :value="$slider->url ?? old('url')"
                                required autocomplete="off" />
                        </div>

                        {{-- Status toggle --}}
                        <div class="mb-4">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <span class="relative inline-flex">
                                    <input type="checkbox" class="peer sr-only" name="status" id="status"
                                        @isset($slider) {{ $slider->status ? 'checked' : '' }} @else checked @endisset>
                                    <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_feature toggle --}}
                        <div class="mb-4">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <span class="relative inline-flex">
                                    <input type="checkbox" class="peer sr-only" name="is_feature" id="is_feature"
                                        @isset($slider) {{ $slider->is_feature ? 'checked' : '' }} @else checked @endisset>
                                    <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                                </span>
                                <span class="text-sm font-medium text-slate-700">is_features</span>
                            </label>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_pop toggle --}}
                        <div class="mb-4">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <span class="relative inline-flex">
                                    <input type="checkbox" class="peer sr-only" name="is_pop" id="is_pop"
                                        @isset($slider) {{ $slider->is_pop ? 'checked' : '' }} @else checked @endisset>
                                    <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                                </span>
                                <span class="text-sm font-medium text-slate-700">is_popup</span>
                            </label>
                            @error('is_pop')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_sub toggle --}}
                        <div class="mb-4">
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <span class="relative inline-flex">
                                    <input type="checkbox" class="peer sr-only" name="is_sub" id="is_sub"
                                        @isset($slider) {{ $slider->is_sub ? 'checked' : '' }} @else checked @endisset>
                                    <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-transform after:content-[''] peer-checked:after:translate-x-5"></span>
                                </span>
                                <span class="text-sm font-medium text-slate-700">Sub Slider</span>
                            </label>
                            @error('is_sub')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            @isset($slider)
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
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush

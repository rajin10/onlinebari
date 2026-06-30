@extends('layouts.admin.app')

@section('title')
    @isset($new_sliders_one)
        Edit Slider One
    @else
        Add Slider One
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
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($new_sliders_one)
                Edit Slider One
            @else
                Add Slider One
            @endisset
        </h1>
        <nav class="text-sm text-slate-500">
            <a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a>
            <span class="mx-1">/</span>
            <span>
                @isset($new_sliders_one)
                    Edit Slider One
                @else
                    Add Slider One
                @endisset
            </span>
        </nav>
    </div>

    {{-- Main content --}}
    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

            {{-- Card header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-semibold text-slate-800">
                    @isset($new_sliders_one)
                        Edit Slider One
                    @else
                        Add New Slider One
                    @endisset
                </h3>
                <x-ui.button variant="danger" :href="routeHelper('sliderone')">
                    <i class="fas fa-long-arrow-alt-left"></i>
                    Back to List One
                </x-ui.button>
            </div>

            {{-- Form wraps body + footer --}}
            <form
                action="{{ isset($new_sliders_one) ? routeHelper('sliderone/' . $new_sliders_one->nsid) : routeHelper('sliderone') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($new_sliders_one)
                    @method('PUT')
                @endisset

                {{-- Card body --}}
                <div class="space-y-4 p-4">

                    {{-- Image upload --}}
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Slider Image:</label>
                        <input type="file" name="image" id="image"
                            class="block w-full @error('image') border border-danger rounded @enderror"
                            @isset($new_sliders_one) data-default-file="{{ '/uploads/sliderOne/' . $slider->image }}" @endisset>
                        @error('image')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slider URL --}}
                    <div class="mb-4">
                        <x-ui.input
                            name="url"
                            label="Slider Url:"
                            type="text"
                            :value="$new_sliders_one->url ?? old('url')"
                            placeholder="Write slider url"
                            required
                            autocomplete="off"
                        />
                    </div>

                    {{-- Status toggle --}}
                    <div class="mb-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input type="checkbox" name="status" id="status"
                                    class="peer sr-only"
                                    @isset($new_sliders_one) {{ $new_sliders_one->status ? 'checked' : '' }} @else checked @endisset>
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-primary"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Status</span>
                        </label>
                        @error('status')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- is_feature toggle --}}
                    <div class="mb-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input type="checkbox" name="is_feature" id="is_feature"
                                    class="peer sr-only"
                                    @isset($new_sliders_one) {{ $new_sliders_one->is_feature ? 'checked' : '' }} @else checked @endisset>
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-primary"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">is_features</span>
                        </label>
                        @error('is_feature')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- is_popup toggle --}}
                    <div class="mb-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input type="checkbox" name="is_pop" id="is_pop"
                                    class="peer sr-only"
                                    @isset($new_sliders_one) {{ $new_sliders_one->is_pop ? 'checked' : '' }} @else checked @endisset>
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-primary"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">is_popup</span>
                        </label>
                        @error('is_pop')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- is_sub toggle --}}
                    <div class="mb-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input type="checkbox" name="is_sub" id="is_sub"
                                    class="peer sr-only"
                                    @isset($new_sliders_one) {{ $new_sliders_one->is_sub ? 'checked' : '' }} @else checked @endisset>
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition-colors peer-checked:bg-primary"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
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
                        @isset($new_sliders_one)
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
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush

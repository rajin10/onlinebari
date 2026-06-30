@extends('layouts.admin.app')

@section('title')
    @isset($banner)
        Edit Banner
    @else
        Add Banner
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
@endpush

@section('content')
    <section class="mb-4">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($banner)
                Edit Banner
            @else
                Add Banner
            @endisset
        </h1>
    </section>

    <section>
        <div class="flex justify-center">
            <div class="w-full md:w-2/3">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                        @isset($banner)
                            Edit Banner
                        @else
                            Add Banner
                        @endisset
                    </div>

                    <form action="{{ isset($banner) ? routeHelper('banner/' . $banner->id) : routeHelper('banner') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($banner)
                            @method('PUT')
                        @endisset

                        <div class="p-4 space-y-4">
                            {{-- Banner Image --}}
                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Banner Image:</label>
                                <input type="file" name="image" id="image"
                                    @isset($banner) data-default-file="{{ asset('uploads/banner/' . $banner->image) }}" @endisset>
                                @error('image')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Banner URL --}}
                            <div class="mb-4">
                                <x-ui.input name="url" label="Banner URL:" :value="$banner->url ?? null" />
                            </div>

                            {{-- Status Toggle --}}
                            <div class="mb-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="status" id="status"
                                        class="sr-only peer"
                                        @isset($banner) {{ $banner->status ? 'checked' : '' }} @else checked @endisset>
                                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    <span class="text-sm font-medium text-slate-700">Status</span>
                                </label>
                            </div>

                            {{-- Feature Toggle --}}
                            <div class="mb-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_feature" id="is_feature"
                                        class="sr-only peer"
                                        @isset($banner) {{ $banner->is_feature ? 'checked' : '' }} @else checked @endisset>
                                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    <span class="text-sm font-medium text-slate-700">Feature</span>
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 px-4 py-3">
                            <x-ui.button type="submit" variant="primary">
                                @isset($banner)
                                    Update
                                @else
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
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush

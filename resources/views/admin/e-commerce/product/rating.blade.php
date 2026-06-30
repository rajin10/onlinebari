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
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">
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
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            {{-- Card header --}}
            <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                <span class="text-base font-semibold text-slate-800">
                    @isset($blog)
                        Edit blog Details
                    @else
                        Add New Campaign
                    @endisset
                </span>
            </div>

            <form action="{{ route('admin.rating.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $rating->id }}" name="id">

                {{-- Card body --}}
                <div class="p-4">
                    {{-- Rating --}}
                    <div class="mb-4">
                        <h4 class="mb-2 text-base font-medium text-slate-700">Give Rating</h4>
                        <div class="rating flex flex-wrap gap-4">
                            <input @if ($rating->rating == 5) checked @endif type="radio" name="rating"
                                value="5" id="5">
                            <label for="5" class="cursor-pointer">5☆</label>
                            <input @if ($rating->rating == 4) checked @endif type="radio" name="rating"
                                value="4" id="4">
                            <label for="4" class="cursor-pointer">4☆</label>
                            <input @if ($rating->rating == 3) checked @endif type="radio" name="rating"
                                value="3" id="3">
                            <label for="3" class="cursor-pointer">3☆</label>
                            <input @if ($rating->rating == 2) checked @endif type="radio" name="rating"
                                value="2" id="2">
                            <label for="2" class="cursor-pointer">2☆</label>
                            <input @if ($rating->rating == 1) checked @endif type="radio" name="rating"
                                value="1" id="1">
                            <label for="1" class="cursor-pointer">1☆</label>
                        </div>
                    </div>

                    {{-- Review textarea --}}
                    <div class="mb-4">
                        <textarea required
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                            name="review" placeholder="what is your view?">{{ $rating->body }}</textarea>
                        @error('review')
                            <small class="text-sm text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Collapsible image upload section --}}
                    <div class="mb-4">
                        <h6 class="rounded border border-slate-300 p-1">
                            <button class="w-full text-left" type="button" data-toggle="collapse"
                                data-target="#BookOpen" aria-expanded="false" aria-controls="BookOpen">
                                Upload More Img:
                                <i style="float: right; top: 8px; position: relative;" class="fas fa-arrow-down"></i>
                            </button>
                        </h6>
                        <div class="spec collapse" id="BookOpen"
                            style="background: #dcdcdc3d; border-radius: 5px; padding: 10px;">
                            <div class="flex flex-wrap gap-4">
                                <div class="w-full rounded border border-slate-300 p-2.5 text-left">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Upload Image</label>
                                    <input type="file" name="report">
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $rating->file }}">
                                        <img style="width:80px;height:auto;border:2px solid black"
                                            src="{{ asset('/') }}uploads/review/{{ $rating->file }}">
                                    </a>
                                </div>
                                <div class="w-full rounded border border-slate-300 p-2.5 text-left">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Upload Image</label>
                                    <input type="file" name="report2">
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $rating->file2 }}">
                                        <img style="width:80px;height:auto;border:2px solid black"
                                            src="{{ asset('/') }}uploads/review/{{ $rating->file2 }}">
                                    </a>
                                </div>
                                <div class="w-full rounded border border-slate-300 p-2.5 text-left">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Upload Image</label>
                                    <input type="file" name="report3">
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $rating->file3 }}">
                                        <img style="width:80px;height:auto;border:2px solid black"
                                            src="{{ asset('/') }}uploads/review/{{ $rating->file3 }}">
                                    </a>
                                </div>
                                <div class="w-full rounded border border-slate-300 p-2.5 text-left">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Upload Image</label>
                                    <input type="file" name="report4">
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $rating->file4 }}">
                                        <img style="width:80px;height:auto;border:2px solid black"
                                            src="{{ asset('/') }}uploads/review/{{ $rating->file4 }}">
                                    </a>
                                </div>
                                <div class="w-full rounded border border-slate-300 p-2.5 text-left">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Upload Image</label>
                                    <input type="file" name="report5">
                                    <a target="_blank" href="{{ asset('/') }}uploads/review/{{ $rating->file5 }}">
                                        <img style="width:80px;height:auto;border:2px solid black"
                                            src="{{ asset('/') }}uploads/review/{{ $rating->file5 }}">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button variant="primary" type="submit">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </div>
            </form>
        </div>
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

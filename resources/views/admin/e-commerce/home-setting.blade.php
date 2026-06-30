@extends('layouts.admin.app')

@section('title', 'Settings')


@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')

    {{-- Page header --}}
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Setting</h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mx-1 before:content-['/']">My Profile</li>
        </ol>
    </div>

    {{-- Main content --}}
    <x-ui.card>
        <x-slot:header>Application Settings</x-slot:header>

        <div class="flex justify-center">
            <div class="w-full max-w-2xl">

                <x-ui.card>
                    <x-slot:header>Setting</x-slot:header>

                    <form action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="type" value="3">

                        <div class="mb-4">
                            <label for="mega" class="mb-1 block text-sm font-medium capitalize text-slate-700">Mega Category</label>
                            <select name="mega[]" id="mega" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\Category::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($mega_cat->value)) @foreach (json_decode($mega_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="sub" class="mb-1 block text-sm font-medium capitalize text-slate-700">Sub Category</label>
                            <select name="sub[]" id="sub" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\SubCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($sub_cat->value)) @foreach (json_decode($sub_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="mini" class="mb-1 block text-sm font-medium capitalize text-slate-700">Mini Category</label>
                            <select name="mini[]" id="mini" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\miniCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($mini_cat->value)) @foreach (json_decode($mini_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="extra" class="mb-1 block text-sm font-medium capitalize text-slate-700">Extra Mini Category</label>
                            <select name="extra[]" class="select2 form-control w-full" id="extra" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\ExtraMiniCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($extra_cat->value)) @foreach (json_decode($extra_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <x-ui.button type="submit" variant="success">
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            </x-ui.button>
                        </div>

                    </form>

                </x-ui.card>

            </div>
        </div>

    </x-ui.card>

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

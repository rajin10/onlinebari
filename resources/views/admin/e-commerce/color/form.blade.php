@extends('layouts.admin.app')

@section('title')
    @isset($color)
        Edit Color
    @else
        Add Color
    @endisset
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
@endpush

@section('content')
    {{-- Page header --}}
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($color)
                Edit Color
            @else
                Add Color
            @endisset
        </h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mr-1 before:content-['/']">
                @isset($color)
                    Edit Color
                @else
                    Add Color
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
                        @isset($color)
                            Edit Color
                        @else
                            Add New Color
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('color')" size="sm">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>

                <form action="{{ isset($color) ? routeHelper('color/' . $color->id) : routeHelper('color') }}"
                    method="POST">
                    @csrf
                    @isset($color)
                        @method('PUT')
                    @endisset

                    {{-- Card body --}}
                    <div class="space-y-4 p-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Write name"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $color->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Color picker --}}
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 mb-1">Choice Color:</label>
                            <input type="text" name="color" id="color"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('color') border-danger @else border-slate-300 @enderror"
                                placeholder="Choice color to color picker" value="{{ $color->code ?? '' }}" required
                                autocomplete="off">
                            @error('color')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description:</label>
                            <textarea name="description" id="description" cols="5" rows="4"
                                placeholder="Write category description"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('description') border-danger @else border-slate-300 @enderror">{{ $color->description ?? old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="status" id="status"
                                    class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                                    @isset($color) {{ $color->status ? 'checked' : '' }} @else checked @endisset>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            @isset($color)
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $('#color').colorpicker();
    </script>
@endpush

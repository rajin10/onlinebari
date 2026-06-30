@extends('layouts.admin.app')

@section('title')
    @isset($size)
        Edit Size
    @else
        Add Size
    @endisset
@endsection

@section('content')
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($size)
                    Edit Size
                @else
                    Add Size
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-slate-700">
                    @isset($size)
                        Edit Size
                    @else
                        Add Size
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    <section>
        <div class="mx-auto max-w-2xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="font-medium text-slate-900">
                        @isset($size)
                            Edit Size
                        @else
                            Add New Size
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('size')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>

                <form action="{{ isset($size) ? routeHelper('size/' . $size->id) : routeHelper('size') }}"
                    method="POST">
                    @csrf
                    @isset($size)
                        @method('PUT')
                    @endisset

                    <div class="p-4">
                        <div class="mb-4">
                            <x-ui.input
                                name="name"
                                label="Size Name:"
                                placeholder="Write size name"
                                :value="$size->name ?? old('name')"
                                required
                                autocomplete="off"
                            />
                        </div>

                        <div class="mb-4">
                            <x-ui.textarea name="description" label="Description:" :rows="5" placeholder="Write size description">{{ $size->description ?? old('description') }}</x-ui.textarea>
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status" id="status"
                                    @isset($size) {{ $size->status ? 'checked' : '' }} @else checked @endisset>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            @isset($size)
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
    </section>
@endsection

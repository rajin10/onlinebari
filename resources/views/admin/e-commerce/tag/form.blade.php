@extends('layouts.admin.app')

@section('title')
    @isset($tag)
        Edit Tag
    @else
        Add Tag
    @endisset
@endsection

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($tag)
                    Edit Tag
                @else
                    Add Tag
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">
                    @isset($tag)
                        Edit Tag
                    @else
                        Add Tag
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <div class="mx-auto max-w-3xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="font-semibold text-slate-800">
                        @isset($tag)
                            Edit Tag
                        @else
                            Add New Tag
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('tag')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>

                {{-- Form (wraps body + footer, matching original structure) --}}
                <form action="{{ isset($tag) ? routeHelper('tag/' . $tag->id) : routeHelper('tag') }}" method="POST">
                    @csrf
                    @isset($tag)
                        @method('PUT')
                    @endisset

                    {{-- Card body --}}
                    <div class="space-y-4 p-4">
                        <x-ui.input
                            name="name"
                            label="Tag Name:"
                            placeholder="Write size name"
                            :value="$tag->name ?? old('name')"
                            required
                            autocomplete="off"
                        />

                        <x-ui.textarea
                            name="description"
                            label="Description:"
                            :rows="5"
                            placeholder="Write size description"
                        >{{ $tag->description ?? old('description') }}</x-ui.textarea>

                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="status"
                                    id="status"
                                    class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                                    @isset($size) {{ $size->status ? 'checked' : '' }} @else checked @endisset
                                >
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button variant="primary" type="submit">
                            @isset($tag)
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

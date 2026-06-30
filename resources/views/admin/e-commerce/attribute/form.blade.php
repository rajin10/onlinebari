@extends('layouts.admin.app')

@section('title')
    @isset($attribute)
        Edit attribute
    @else
        Add attribute
    @endisset
@endsection

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($attribute)
                    Edit attribute
                @else
                    Add attribute
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">
                    @isset($attribute)
                        Edit attribute
                    @else
                        Add attribute
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
                    <h3 class="font-medium text-slate-900">
                        @isset($attribute)
                            Edit attribute
                        @else
                            Add New attribute
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('attribute/list')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>

                <form
                    action="{{ isset($attribute) ? routeHelper('attribute/' . $attribute->id) : route('admin.attribute.store') }}"
                    method="POST">
                    @csrf
                    @isset($attribute)
                        @method('PUT')
                    @endisset

                    {{-- Card body --}}
                    <div class="p-4">
                        <div class="mb-4">
                            <label for="category_id" class="mb-1 block text-sm font-medium text-slate-700">Category name:</label>
                            <select name="category_id" id="category_id" class="category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option
                                        @isset($attribute){{ $attribute->category_id == $category->id ? 'selected' : '' }}@endisset
                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-ui.input
                                name="name"
                                label="attribute Name:"
                                type="text"
                                :value="$attribute->name ?? old('name')"
                                placeholder="Write attribute name"
                                required
                                autocomplete="off"
                            />
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <div class="mb-4">
                            <x-ui.button variant="primary" type="submit">
                                @isset($attribute)
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                @else
                                    <i class="fas fa-plus-circle"></i>
                                    Submit
                                @endisset
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

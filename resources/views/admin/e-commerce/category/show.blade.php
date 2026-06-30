@extends('layouts.admin.app')

@section('title', 'Category Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Category Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Show Category</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            {{-- Card header --}}
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">Category Information</h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" :href="routeHelper('category/' . $category->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('category')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            {{-- Card body --}}
            <div class="p-4">
                <x-ui.table>
                    <tbody>
                        <tr>
                            <th>Cover Photo</th>
                            <td>
                                @if ($category->cover_photo == 'default.png')
                                    <img src="https://via.placeholder.com/150" alt="Cover Photo" width="150px"
                                        height="150px">
                                @else
                                    <img src="/uploads/category/{{ $category->cover_photo }}" alt="Cover Photo"
                                        width="150px" height="150px">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $category->description }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($category->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>is_features</th>
                            <td>
                                @if ($category->is_feature)
                                    <x-ui.badge variant="success">Yes</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">No</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Show on homepage</th>
                            <td>
                                <x-ui.badge variant="{{ $category->is_shown_on_homepage ? 'success' : 'danger' }}">
                                    {{ $category->is_shown_on_homepage ? 'Yes' : 'No' }}
                                </x-ui.badge>
                            </td>
                        </tr>
                    </tbody>
                </x-ui.table>
            </div>
        </div>

    </section>

@endsection

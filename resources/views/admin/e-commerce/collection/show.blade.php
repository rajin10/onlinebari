@extends('layouts.admin.app')

@section('title', 'Category Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Category Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Show Category</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Category Information</h3>
                    <div class="flex gap-2">
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
            </x-slot:header>

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
                </tbody>
            </x-ui.table>

        </x-ui.card>

    </section>

@endsection

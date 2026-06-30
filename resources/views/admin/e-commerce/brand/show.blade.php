@extends('layouts.admin.app')

@section('title', 'Brand Information')

@section('content')

    <section class="mb-4">
        <div>
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-2xl font-semibold text-slate-800">Brand Information</h1>
                <ol class="flex items-center gap-1 text-sm text-slate-500">
                    <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                    <li><span class="mx-1">/</span></li>
                    <li class="text-slate-700 font-medium">Show Brand</li>
                </ol>
            </div>
        </div>
    </section>

    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Brand Information</h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" :href="routeHelper('brand/' . $brand->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('brand')">
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
                        @if ($brand->cover_photo == 'default.png')
                            <img src="https://via.placeholder.com/150" alt="Cover Photo" width="150px"
                                height="150px">
                        @else
                            <img src="/uploads/brand/{{ $brand->cover_photo }}" alt="Cover Photo" width="150px"
                                height="150px">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $brand->name }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $brand->description }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($brand->status)
                            <x-ui.badge variant="success">Active</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger">Disable</x-ui.badge>
                        @endif
                    </td>
                </tr>
            </tbody>
        </x-ui.table>
    </x-ui.card>

@endsection

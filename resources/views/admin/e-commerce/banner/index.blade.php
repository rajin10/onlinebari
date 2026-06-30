@extends('layouts.admin.app')

@section('title', 'Banner List')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-semibold text-slate-800">Banner List</h1>
    </div>

    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <span>Banner List</span>
                <x-ui.button variant="success" :href="routeHelper('banner/create')">Add Banner</x-ui.button>
            </div>
        </x-slot:header>

        <x-ui.table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Image</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banners as $key => $banner)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><img src="{{ asset('uploads/banner/' . $banner->image) }}" width="200"></td>
                        <td>{{ $banner->url }}</td>
                        <td>
                            @if ($banner->status)
                                Active
                            @else
                                Disabled
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <x-ui.button variant="info" size="sm" :href="routeHelper('banner/' . $banner->id . '/edit')">Edit</x-ui.button>
                                <x-ui.button variant="warning" size="sm" :href="routeHelper('banner/' . $banner->id)">
                                    @if ($banner->status)
                                        Disable
                                    @else
                                        Enable
                                    @endif
                                </x-ui.button>
                                <form action="{{ routeHelper('banner/' . $banner->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button variant="danger" size="sm" type="submit">Delete</x-ui.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-ui.table>
    </x-ui.card>
@endsection

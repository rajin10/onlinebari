@extends('layouts.admin.app')

@section('title', 'Color Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Color Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Show Color</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

            {{-- Card header --}}
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">Color Information</h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" :href="routeHelper('color/' . $color->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('color')">
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
                            <th width="15%">Color Name</th>
                            <td width="85%">{{ $color->name }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Color Code</th>
                            <td width="85%">{{ $color->code }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Color Overview</th>
                            <td width="85%">
                                <div style="width:50%;height:50px;background:{{ $color->code }}"></div>
                            </td>
                        </tr>
                        <tr>
                            <th width="15%">Description</th>
                            <td width="85%">{{ $color->description }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Status</th>
                            <td width="85%">
                                @if ($color->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </x-ui.table>
            </div>

        </div>

    </section>

@endsection

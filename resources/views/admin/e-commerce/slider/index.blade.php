@extends('layouts.admin.app')

@section('title', 'Slider List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Slider List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Slider List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                <h3 class="font-medium text-slate-900">Slider List</h3>
                <x-ui.button variant="success" :href="routeHelper('slider/create')">
                    <i class="fas fa-plus-circle"></i>
                    Add Size
                </x-ui.button>
            </div>

            <div class="p-4">
                <x-ui.table id="example1">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Image</th>
                            <th>Url</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="/uploads/slider/{{ $data->image }}" alt="Slider" width="200px">
                                </td>
                                <td>{{ $data->url }}</td>
                                <td>
                                    @if ($data->status)
                                        <x-ui.badge variant="success">Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger">Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="flex items-center gap-1">
                                    @if ($data->status)
                                        <x-ui.button variant="warning" size="sm" title="Disable"
                                            :href="routeHelper('slider/' . $data->id)">
                                            <i class="fas fa-thumbs-up"></i>
                                        </x-ui.button>
                                    @else
                                        <x-ui.button variant="warning" size="sm" title="Active"
                                            :href="routeHelper('slider/' . $data->id)">
                                            <i class="fas fa-thumbs-down"></i>
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button variant="info" size="sm"
                                        :href="routeHelper('slider/' . $data->id . '/edit')">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="danger" size="sm"
                                        href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </x-ui.button>
                                    <form id="delete-data-form-{{ $data->id }}"
                                        action="{{ routeHelper('slider/' . $data->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-ui.table>
            </div>
        </div>

    </section>

@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable();
        })
    </script>
@endpush

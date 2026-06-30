@extends('layouts.admin.app')

@section('title', 'Collection List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Collection List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Collection List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span>Collection List</span>
                    <x-ui.button variant="success" size="sm" :href="routeHelper('collection/create')">
                        <i class="fas fa-plus-circle"></i>
                        Add Collection
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Cover Photo</th>
                        <th>Name</th>
                        <th>Categories</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($collections as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($data->cover_photo == 'default.png')
                                    <img src="https://via.placeholder.com/150" alt="Cover Photo" width="60px">
                                @else
                                    <img src="/uploads/collection/{{ $data->cover_photo }}" alt="Cover Photo"
                                        width="60px">
                                @endif
                            </td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->categories->count() }}</td>
                            <td>
                                @if ($data->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('collection/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                @if ($data->status)
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('collection/' . $data->id)">
                                        <i class="fas fa-thumbs-up"></i>
                                    </x-ui.button>
                                @else
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('collection/' . $data->id)">
                                        <i class="fas fa-thumbs-down"></i>
                                    </x-ui.button>
                                @endif

                                <a href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData"
                                    class="inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors h-8 px-3 text-sm bg-danger text-white hover:opacity-90">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </a>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('collection/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
        </x-ui.card>

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

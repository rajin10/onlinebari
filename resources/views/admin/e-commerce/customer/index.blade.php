@extends('layouts.admin.app')

@section('title', 'Customer List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Customer List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Product List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span>Customer List</span>
                    <x-ui.button variant="success" size="sm" :href="routeHelper('customer/create')">
                        <i class="fas fa-plus-circle"></i>
                        Add Customer
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->username }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>
                                @if ($data->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                @if ($data->status)
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('user/status/' . $data->id)" title="Disable">
                                        <i class="fas fa-lock-open"></i>
                                    </x-ui.button>
                                @else
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('user/status/' . $data->id)" title="Active">
                                        <i class="fas fa-lock"></i>
                                    </x-ui.button>
                                @endif
                                <x-ui.button variant="primary" size="sm" :href="routeHelper('customer/' . $data->id)">
                                    <i class="fas fa-eye"></i>
                                </x-ui.button>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('customer/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <a href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData"
                                    class="inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors h-8 px-3 text-sm bg-danger text-white hover:opacity-90">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </a>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('customer/' . $data->id) }}" method="POST">
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
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/jszip/jszip.min.js"></script>
    <script src="/assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        })
    </script>
@endpush

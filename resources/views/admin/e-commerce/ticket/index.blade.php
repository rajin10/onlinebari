@extends('layouts.admin.app')

@section('title', 'Ticket List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Ticket List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Ticket List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            @if (!empty(Session::get('massage2')))
                <x-ui.alert variant="success" class="mb-4 text-center">
                    {{ Session::get('massage2') }}
                </x-ui.alert>
            @endif

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Username</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->user->username ?? '' }}</td>
                            <td>{{ $data->sub }}</td>
                            <td>
                                @if ($data->status == 0)
                                    <x-ui.badge variant="success">review</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">reply</x-ui.badge>
                                @endif
                            </td>
                            <td class="flex items-center gap-1">
                                <x-ui.button variant="info" size="sm" :href="route('admin.ticket.show', ['show' => $data->id])">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" :href="route('admin.ticket.delete', ['ticket' => $data->id])">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>
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

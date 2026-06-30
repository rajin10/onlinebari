@extends('layouts.admin.app')

@section('title', 'Campaing List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Campaing List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Campaing List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Campaing List</h3>
                    <x-ui.button variant="success" :href="route('admin.campaing.create')" size="sm">
                        <i class="fas fa-plus-circle"></i>
                        Add Campaing
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $data->user_id ? $data->users->name : 'Guest' }}</td>
                            <td>{{ $data->user_id ? $data->users->email : 'Guest' }}</td>
                            <td>{{ $data->comment }}</td>

                            <td>
                                <a href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData"
                                    class="inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors bg-danger text-white hover:opacity-90 h-8 px-3 text-sm">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </a>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('campaing/comment/delete/' . $data->id) }}" method="POST">
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

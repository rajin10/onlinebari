@extends('layouts.admin.app')

@section('title', 'attribute List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">attribute List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">attribute List</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span>attribute List</span>
                    <x-ui.button variant="success" size="sm" :href="route('admin.attribute.form')">
                        <i class="fas fa-plus-circle"></i>
                        Add attribute
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attributes as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->category->name ?? '' }}</td>

                            <td>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('attribute/value/' . $data->id)">
                                    <i class="fas fa-plus-circle"></i>
                                </x-ui.button>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('attribute/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('attribute/delete/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
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

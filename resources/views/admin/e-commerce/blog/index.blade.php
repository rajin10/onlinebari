@extends('layouts.admin.app')

@section('title', 'Blogs List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Blogs List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Blogs List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span>Blogs List</span>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        @if ($is == 1)
                            <th>User</th>
                        @endif
                        <th>Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blogs as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            @if ($is == 1)
                                <td><a href="customer/{{ $data->user_id }}" class="text-primary hover:underline">{{ $data->user->name }}</a></td>
                            @endif
                            <td>{{ $data->title }}</td>
                            <td>
                                @if ($data->status == 1)
                                    <span class="inline-block rounded px-2 py-1 text-sm bg-success text-white">Active</span>
                                @else
                                    <span class="inline-block rounded px-2 py-1 text-sm bg-danger text-white">Dective</span>
                                @endif
                            </td>

                            <td>
                                <div class="flex items-center gap-1">
                                    @if ($data->status)
                                        <x-ui.button variant="warning" size="sm" :href="routeHelper('blog/status/' . $data->id)">
                                            <i class="fas fa-thumbs-up"></i>
                                        </x-ui.button>
                                    @else
                                        <x-ui.button variant="warning" size="sm" :href="routeHelper('blog/status/' . $data->id)">
                                            <i class="fas fa-thumbs-down"></i>
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button variant="info" size="sm" :href="routeHelper('blog-edit/' . $data->id)">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>

                                    <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </x-ui.button>
                                    <form id="delete-data-form-{{ $data->id }}"
                                        action="{{ routeHelper('blog-delete/' . $data->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
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

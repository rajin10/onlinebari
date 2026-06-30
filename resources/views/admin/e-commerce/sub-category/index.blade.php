@extends('layouts.admin.app')

@section('title', 'Sub Category List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="">
            <div class="flex flex-wrap items-center justify-between mb-2">
                <div class="w-full sm:w-auto">
                    <h1 class="text-2xl font-semibold text-slate-800">Sub Category List</h1>
                </div>
                <div class="w-full sm:w-auto">
                    <ol class="flex items-center gap-1 text-sm text-slate-500 sm:justify-end">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li class="before:content-['/'] before:mx-1">Category List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="mb-6">

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3 flex flex-wrap items-center justify-between gap-2">
                <h3 class="font-medium text-slate-900">Sub Category List</h3>
                <x-ui.button variant="success" :href="routeHelper('sub-category/create')">
                    <i class="fas fa-plus-circle"></i>
                    Add Sub Category
                </x-ui.button>
            </div>
            <div class="p-4">
                <x-ui.table id="example1">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Cover Photo</th>
                            <th>Category Name</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sub_categories as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($data->cover_photo == 'default.png')
                                        <img src="https://via.placeholder.com/150" alt="Cover Photo" width="60px">
                                    @else
                                        <img src="/uploads/sub category/{{ $data->cover_photo }}" alt="Cover Photo"
                                            width="60px">
                                    @endif

                                </td>
                                <td>{{ $data->category->name ?? '' }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    @if ($data->status)
                                        <x-ui.badge variant="success">Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger">Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td>
                                    <x-ui.button variant="success" size="sm"
                                        :href="route('subCategory.product', ['slug' => $data->slug])">
                                        <i class="fas fa-box"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="info" size="sm"
                                        :href="routeHelper('sub-category/' . $data->id . '/edit')">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="danger" size="sm" href="javascript:void(0)"
                                        data-id="{{ $data->id }}" id="deleteData">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </x-ui.button>
                                    <form id="delete-data-form-{{ $data->id }}"
                                        action="{{ routeHelper('sub-category/' . $data->id) }}" method="POST">
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

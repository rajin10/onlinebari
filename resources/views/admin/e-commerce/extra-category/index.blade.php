@extends('layouts.admin.app')

@section('title', 'Extra Category List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Extra Category List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Category List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span>Extra Category List</span>
                    <x-ui.button variant="success" size="sm" :href="route('admin.extracategory')">
                        <i class="fas fa-plus-circle"></i>
                        Add Extra Category
                    </x-ui.button>
                </div>
            </x-slot:header>

            @if (!empty(Session::get('massage2')))
                <x-ui.alert variant="success" class="mb-4 text-center">
                    {{ Session::get('massage2') }}
                </x-ui.alert>
            @endif

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
                    @foreach ($mini_categories as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($data->cover_photo == 'default.png')
                                    <img src="https://via.placeholder.com/150" alt="Cover Photo" width="60px">
                                @else
                                    <img src="/uploads/extra-category/{{ $data->cover_photo }}" alt="Cover Photo"
                                        width="60px">
                                @endif

                            </td>
                            <td><?php if (! empty($data->miniCategory->name)) {
                                echo $data->miniCategory->name;
                            } ?></td>
                            <td>{{ $data->name }}</td>
                            <td>
                                @if ($data->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td class="flex items-center gap-1">
                                <x-ui.button variant="success" size="sm"
                                    :href="route('extraCategory.product', ['slug' => $data->slug])">
                                    <i class="fas fa-box"></i>
                                </x-ui.button>
                                <x-ui.button variant="info" size="sm"
                                    :href="route('admin.extracategory.edit', ['edit' => $data->id])">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm"
                                    :href="route('admin.extracategory.delete', ['did' => $data->id])">
                                    <i class="fas fa-trash-alt"></i>
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

@extends('layouts.admin.app')

@section('title', 'Unaproved Product List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="">
            <div class="flex flex-wrap items-center justify-between mb-2">
                <div class="w-full sm:w-1/2">
                    <h1 class="text-2xl font-semibold text-slate-800">Unaproved Product List</h1>
                </div>
                <div class="w-full sm:w-1/2 flex justify-end">
                    <ol class="flex items-center gap-1 text-sm text-slate-500">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li class="text-slate-400">/</li>
                        <li class="text-slate-600">Unaproved Product List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="mb-6">

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Disable Product List</h3>
                    <x-ui.button variant="success" :href="routeHelper('product/create')">
                        <i class="fas fa-plus-circle"></i>
                        Add Product
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>RP</th>
                        <th>DP</th>
                        <th>Stock</th>
                        <th>Brand</th>
                        <th>is_aproved</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <img src="{{ asset('uploads/product/' . $data->image) }}" alt="Product Image"
                                    width="60px">
                            </td>
                            <td>{{ $data->title }}</td>
                            <td>{{ $data->regular_price }}</td>
                            <td>{{ $data->discount_price }}</td>
                            <td>
                                @if ($data->quantity > 0)
                                    <x-ui.badge variant="success">Available</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Unavailable</x-ui.badge>
                                @endif
                            </td>
                            <td>{{ $data->brand->name ?? '' }}</td>
                            <td>
                                <x-ui.badge variant="danger">Unapproved</x-ui.badge>
                            </td>
                            <td>
                                <div class="inline-flex items-center gap-1">
                                    <x-ui.button variant="primary" size="sm" :href="route('admin.product.order', $data->id)" title="Order Product">
                                        <i class="fab fa-jedi-order"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="danger" size="sm" :href="routeHelper('product/status/' . $data->id)" title="Disable">
                                        <i class="fas fa-thumbs-down"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="primary" size="sm" :href="routeHelper('product/' . $data->id)">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="info" size="sm" :href="routeHelper('product/' . $data->id . '/edit')">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    @if (auth()->user()->desig != 3)
                                        <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                            <i class="nav-icon fas fa-trash-alt"></i>
                                        </x-ui.button>
                                    @endif
                                    <form id="delete-data-form-{{ $data->id }}"
                                        action="{{ routeHelper('product/' . $data->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>RP</th>
                        <th>DP</th>
                        <th>Stock</th>
                        <th>Brand</th>
                        <th>Status</th>
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

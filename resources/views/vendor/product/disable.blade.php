@extends('layouts.vendor.app')

@section('title', 'Disable Product List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Disable Product List</h1>
        <nav class="text-sm text-slate-500">
            <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
            <span class="mx-1">/</span>
            <span>Disable Product List</span>
        </nav>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="font-medium text-slate-900">Disable Product List</h3>
            <x-ui.button variant="primary" :href="routeHelper('product/create')" size="sm">
                <i class="fas fa-plus-circle"></i>
                Add Product
            </x-ui.button>
        </div>

        <div class="p-4">
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
                        <th>Status</th>
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
                                @if ($data->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button :href="route('vendor.product.order', $data->id)" variant="primary" size="sm" title="Order Product">
                                        <i class="fab fa-jedi-order"></i>
                                    </x-ui.button>
                                    @if ($data->status)
                                        <x-ui.button :href="routeHelper('product/status/' . $data->id)" variant="primary" size="sm" title="Disable">
                                            <i class="fas fa-thumbs-up"></i>
                                        </x-ui.button>
                                    @else
                                        <x-ui.button :href="routeHelper('product/status/' . $data->id)" variant="secondary" size="sm" title="Active">
                                            <i class="fas fa-thumbs-down"></i>
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button :href="routeHelper('product/' . $data->id)" variant="primary" size="sm">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <x-ui.button :href="routeHelper('product/' . $data->id . '/edit')" variant="secondary" size="sm">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    <x-ui.button href="javascript:void(0)" variant="danger" size="sm" id="deleteData">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </x-ui.button>
                                    <form id="delete-data-form" action="{{ routeHelper('product/' . $data->id) }}"
                                        method="POST">
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
        </div>
    </div>

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

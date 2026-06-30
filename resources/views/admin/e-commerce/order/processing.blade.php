@extends('layouts.admin.app')

@section('title', 'Processing Order List')

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
            <h1 class="text-2xl font-semibold text-slate-800">Processing Order List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Processing Order List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>Processing Order List</x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Payment</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->first_name }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->payment_method }}</td>
                            <td>{{ $data->subtotal }}</td>
                            <td>{{ $data->discount }}</td>
                            <td>{{ $data->total }}</td>
                            <td>{{ date('d M Y', strtotime($data->created_at)) }}</td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button variant="warning" size="sm" :href="route('admin.order.invoice', $data->id)" title="Invoice" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </x-ui.button>

                                    <x-ui.button variant="info" size="sm" :href="routeHelper('order/' . $data->id)" title="Show Information">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>

                                    <x-ui.button variant="danger" size="sm" :href="routeHelper('order/status/cancel/' . $data->id)" title="Cancel" onclick="return confirm('Are you sure cancel this order?')">
                                        <i class="fas fa-window-close"></i>
                                    </x-ui.button>

                                    <x-ui.button variant="success" size="sm" :href="routeHelper('order/status/shipping/' . $data->id)" id="btnShipping" title="Shipping" onclick="return confirm('Are you sure Shipping this order?')">
                                        <i class="fas fa-plane"></i>
                                    </x-ui.button>
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

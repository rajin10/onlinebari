@extends('layouts.vendor.app')

@section('title', 'Order List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Order List</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a>
                <span class="mx-1">/</span>
                <span>Order List</span>
            </nav>
        </div>
    </section>

    <section>

        <x-ui.card>
            <x-slot:header>Order List</x-slot:header>

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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $data)
                        @php
                            $order_dt = DB::table('multi_order')
                                ->where('order_id', $data->id)
                                ->where('vendor_id', auth()->id())
                                ->first() ?? (object) ['total' => 0, 'discount' => 0];
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->first_name }}</td>
                            <td>{{ $data->phone }}</td>
                            <td>{{ $data->payment_method }}</td>
                            <td>{{ $order_dt->total }}</td>
                            <td>{{ $order_dt->discount }}</td>
                            <td>{{ $order_dt->total - $order_dt->discount }}</td>
                            <td>{{ date('d M Y', strtotime($data->created_at)) }}</td>
                            <td>
                                @if ($data->status == 0)
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @elseif ($data->status == 1)
                                    <x-ui.badge variant="primary">Processing</x-ui.badge>
                                @elseif ($data->status == 2)
                                    <x-ui.badge variant="danger">Canceled</x-ui.badge>
                                @elseif ($data->status == 5)
                                    <x-ui.badge variant="danger">refund</x-ui.badge>
                                @elseif ($data->status == 4)
                                    <x-ui.badge variant="primary">shipping</x-ui.badge>
                                @else
                                    <x-ui.badge variant="success">Delivered</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button variant="warning" size="sm" href="{{ route('vendor.order.invoice', $data->id) }}" title="Invoice" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="info" size="sm" href="{{ routeHelper('order/' . $data->id) }}" title="Show Information">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <!--   @if ($data->status == 0)
<a title="Processing" href="{{ routeHelper('order/status/processing/' . $data->id) }}" id="btnStatus" onclick="return confirm('Are you sure change this order status?')" class="btn btn-primary btn-sm">
                                        <i class="fas fa-running"></i>
                                    </a>
                                    <a title="Cancel" href="{{ routeHelper('order/status/cancel/' . $data->id) }}" id="btnCancel" onclick="return confirm('Are you sure cancel this order?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-window-close"></i>
                                    </a>
                                    <a title="Delivered" href="{{ routeHelper('order/status/delivered/' . $data->id) }}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                        <i class="fas fa-thumbs-up"></i>
                                    </a>
@elseif ($data->status == 1)
<a title="Cancel" href="{{ routeHelper('order/status/cancel/' . $data->id) }}" id="btnCancel" onclick="return confirm('Are you sure cancel this order?')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-window-close"></i>
                                        </a>
                                        <a title="Delivered" href="{{ routeHelper('order/status/delivered/' . $data->id) }}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                            <i class="fas fa-thumbs-up"></i>
                                        </a>
@endif -->
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

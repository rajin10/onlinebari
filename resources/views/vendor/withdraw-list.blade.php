@extends('layouts.vendor.app')

@section('title', 'Withdraw List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Order List</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
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
                        <th>Method</th>
                        <th>Anount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdraws as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @php
                                    if ($data->payment_method == 1) {
                                        echo 'Bkash';
                                    } elseif ($data->payment_method == 2) {
                                        echo 'Nagad';
                                    } elseif ($data->payment_method == 3) {
                                        echo 'Rocket';
                                    } else {
                                        echo 'Bank';
                                    }
                                @endphp
                            </td>
                            <td>{{ $data->amount }}</td>
                            <td>{{ date('d M Y', strtotime($data->created_at)) }}</td>
                            <td>
                                @if ($data->status == 0)
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @elseif ($data->status == 1)
                                    <x-ui.badge variant="primary">Complete</x-ui.badge>
                                @elseif ($data->status == 2)
                                    <x-ui.badge variant="danger">Canceled</x-ui.badge>
                                @endif
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
            $("#example1").DataTable();


        })
    </script>
@endpush

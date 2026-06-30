@extends('layouts.admin.app')

@section('title', 'Withdraw List')

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
            <h1 class="text-2xl font-semibold text-slate-800">Withdraw List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Withdraw List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>
                <span class="text-base font-semibold text-slate-800">Order List</span>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Account</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdraws as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->user->name }}</td>
                            <td>
                                @php
                                    if ($data->payment_method == 1) {
                                        echo 'Bkash';
                                        $method = 'bkash';
                                    } elseif ($data->payment_method == 2) {
                                        echo 'Nagad';
                                        $method = 'nogod';
                                    } elseif ($data->payment_method == 3) {
                                        echo 'Rocket';
                                        $method = 'rocket';
                                    } elseif ($data->payment_method == 4) {
                                        echo 'Mobile Recharge';
                                    } else {
                                        echo 'Bank';
                                        $method = 'bank';
                                    }
                                @endphp
                            </td>
                            <td>{{ $data->amount }}</td>
                            <td>{{ $data->number != null ? $data->number : $data->user->shop_info->$method }}</td>
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
                            <td>
                                {{-- <x-ui.button variant="primary" size="sm" :href="routeHelper('vendor/' . $data->user_id)">
                                    <i class="fas fa-eye"></i>
                                </x-ui.button> --}}
                                <x-ui.button variant="danger" size="sm" :href="route('admin.vendor.withdraw.delete', ['id' => $data->id])">
                                    <i class="fas fa-trash"></i>
                                </x-ui.button>
                                @if ($data->status == 0)
                                    <x-ui.button variant="success" size="sm" :href="route('admin.vendor.withdraw.aprove', ['id' => $data->id])">
                                        <i class="fas fa-thumbs-up"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="warning" size="sm" :href="route('admin.vendor.withdraw.cancel', ['id' => $data->id])">
                                        <i class="fas fa-times"></i>
                                    </x-ui.button>
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

@extends('layouts.admin.app')

@section('title', 'Partials Pay List')

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
            <h1 class="text-2xl font-semibold text-slate-800">Partials Pay List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Partials Pay List</li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>

        <x-ui.card>
            <x-slot:header>Partials Pay List</x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>Invoice</th>
                        <th>amount</th>
                        <th>tnx</th>
                        <th>method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partials as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $order->order->invoice ?? '' }}</td>
                            <td>{{ $order->amount }}</td>
                            <td>{{ $order->transaction_id }}</td>
                            <td>
                                @if ($order->payment_method == 'bk')
                                    Bkash
                                @elseif($order->payment_method == 'ng')
                                    Nagad
                                @elseif($order->payment_method == 'rk')
                                    Rocket
                                @endif
                            </td>
                            <td>
                                @if ($order->status == 1)
                                    Aprove
                                @elseif($order->status == 2)
                                    Cancel
                                @else
                                    Pending
                                @endif
                            </td>
                            <td>
                                @if ($order->status == 0)
                                    <x-ui.button
                                        variant="success"
                                        size="sm"
                                        :href="route('admin.order.partials.status', ['id' => $order->id, 'st' => '1'])"
                                    >
                                        <i class="fas fa-thumbs-up"></i>
                                    </x-ui.button>
                                    <x-ui.button
                                        variant="danger"
                                        size="sm"
                                        :href="route('admin.order.partials.status', ['id' => $order->id, 'st' => '2'])"
                                    >
                                        <i class="fas fa-times"></i>
                                    </x-ui.button>
                                @elseif($order->status == 1)

                                @elseif($order->status == 2)
                                    <x-ui.button
                                        variant="success"
                                        size="sm"
                                        :href="route('admin.order.partials.status', ['id' => $order->id, 'st' => '1'])"
                                    >
                                        <i class="fas fa-thumbs-up"></i>
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

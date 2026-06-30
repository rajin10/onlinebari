@extends('layouts.admin.app')

@section('title', 'All Order List')

@push('css')
    <!-- <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
          <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
          <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">All Order List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li class="text-slate-700">All Order List</li>
            </ol>
        </div>
    </section>

    <section class="mb-6">

        <x-ui.card>
            <x-slot:header>All Order List</x-slot:header>

            <form action="{{ route('admin.order.index') }}" method="GET" class="mb-4 flex flex-wrap gap-4">
                <div class="w-full md:w-1/4">
                    <x-ui.input
                        name="keyword"
                        label="Search by Invoice or Phone"
                        type="text"
                        :value="request('keyword')"
                        placeholder="Invoice or Phone"
                    />
                </div>

                <div class="w-full md:w-1/5">
                    <x-ui.select name="status" label="Status">
                        <option value="" selected>All</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Processing</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Canceled</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Delivered</option>
                        <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Shipping</option>
                    </x-ui.select>
                </div>

                <div class="w-full md:w-1/5">
                    <x-ui.select name="is_pre" label="Pre Order">
                        <option value="" selected>All</option>
                        <option value="0" {{ request('is_pre') === '0' ? 'selected' : '' }}>Pre Order</option>
                        <option value="1" {{ request('is_pre') === '1' ? 'selected' : '' }}>Not Pre Order</option>
                    </x-ui.select>
                </div>

                <div class="flex items-end gap-2 pb-4">
                    <x-ui.button type="submit" variant="primary">Filter</x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.order.index')">Reset</x-ui.button>
                </div>
            </form>

            <p class="mb-3 text-right text-base font-semibold text-slate-700">Total {{ $orders->total() }} results</p>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Invoice</th>
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
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->invoice }}</td>
                            <td>{{ $data->first_name }}</td>
                            <td>
                                {{ $data->phone }}

                                <button class="fraud_checker inline-flex items-center rounded-full bg-primary px-2.5 py-0.5 text-xs font-medium text-white hover:opacity-90"
                                    data-id="{{ $data->id }}" data-toggle="modal"
                                    data-target="#fraud_checker_modal">
                                    Check Fraud
                                </button>

                            </td>
                            <td>{{ $data->payment_method }}</td>
                            <td>{{ $data->subtotal }}</td>
                            <td>{{ $data->discount }}</td>
                            <td>{{ $data->total }}</td>
                            <td>{{ date('d M Y', strtotime($data->created_at)) }}</td>
                            <td>
                                @if ($data->status == 0)
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @elseif ($data->status == 1)
                                    <x-ui.badge variant="primary">order confirm</x-ui.badge>
                                @elseif ($data->status == 2)
                                    <x-ui.badge variant="danger">Canceled</x-ui.badge>
                                @elseif ($data->status == 5)
                                    <x-ui.badge variant="danger">refund</x-ui.badge>
                                @elseif ($data->status == 4)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white" style="background: #7db1b1;">Shipping</span>
                                @elseif ($data->status == 6)
                                    <x-ui.badge variant="warning"><small>Return<br>Requested</small></x-ui.badge>
                                @elseif ($data->status == 7)
                                    <x-ui.badge variant="warning"><small>Returning by Customer</small></x-ui.badge>
                                @elseif ($data->status == 8)
                                    <x-ui.badge variant="danger">Returned</x-ui.badge>
                                @elseif ($data->status == 9)
                                    <x-ui.badge variant="danger"><small>Sended to Courier</small></x-ui.badge>
                                @elseif ($data->status == 3)
                                    <x-ui.badge variant="success">Delivered</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button variant="warning" size="sm" :href="route('admin.order.invoice', $data->id)" title="Invoice" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="info" size="sm" :href="routeHelper('order/' . $data->id)" title="Show Information">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <!-- @if ($data->status == 0)
    <a title="Done" href="{{ routeHelper('order/status/processing/' . $data->id) }}" id="btnStatus" onclick="return confirm('Are you sure change this order status?')" class="btn btn-primary btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>

                                            <a title="Shipping" href="{{ routeHelper('order/status/shipping/' . $data->id) }}" id="btnShipping" onclick="return confirm('Are you sure Shipping this order?')" class="btn btn-success btn-sm">
                                                <i class="fas fa-plane"></i>
                                            </a>
                                            <a title="Delivered" href="{{ routeHelper('order/status/delivered/' . $data->id) }}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                                <i class="fas fa-thumbs-up"></i>
                                            </a>
@elseif ($data->status == 1)
    <a title="Shipping" href="{{ routeHelper('order/status/shipping/' . $data->id) }}" id="btnShipping" onclick="return confirm('Are you sure Shipping this order?')" class="btn btn-success btn-sm">
                                                <i class="fas fa-plane"></i>
                                                </a>
                                                <a title="Delivered" href="{{ routeHelper('order/status/delivered/' . $data->id) }}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </a>
@elseif ($data->status == 4)
    <a title="Delivered" href="{{ routeHelper('order/status/delivered/' . $data->id) }}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </a>
    @endif
                                            @if ($data->status != 2 && $data->status != 3)
    <a title="Cancel" href="{{ routeHelper('order/status/cancel/' . $data->id) }}" id="btnCancel" onclick="return confirm('Are you sure cancel this order?')" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-window-close"></i>
                                                </a>
    @endif -->
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>
            <div class="mt-4 flex justify-center">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </x-ui.card>

        <!-- fraud checker modal -->
        <form>
            <div class="fixed inset-0 z-50 hidden items-center justify-center p-4" id="fraud_checker_modal" tabindex="-1" role="dialog">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-xl" role="document">
                    <div id="fraud_checker_details">
                        <!-- ajax content -->
                    </div>
                </div>
            </div>

            <!-- task all modal end -->
        </form>
    </section>

@endsection

@push('js')
    <!-- <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
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
            </script> -->


    <script>
        $(document).on("click", ".fraud_checker", function() {
            let id = $(this).data('id');

            $("#fraud_checker_details").html(
                '<div class="text-center p-4">Loading...</div>'
            );

            $.ajax({
                type: "POST",
                url: "{{ route('admin.order.fraud_checker') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $("#fraud_checker_details").html(response);
                },
                error: function() {
                    $("#fraud_checker_details").html(
                        '<div class="text-danger p-3">Something went wrong</div>'
                    );
                }
            });

            $("#fraud_checker_modal").css("display", "flex");
            $("#fraud_checker_modal .absolute.inset-0").on("click", function() {
                $("#fraud_checker_modal").css("display", "none");
            });
        });
    </script>
@endpush

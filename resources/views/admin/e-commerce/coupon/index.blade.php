@extends('layouts.admin.app')

@section('title', 'Coupon List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header -->
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Coupon List</h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="text-slate-400">/</li>
            <li class="text-slate-700">Coupon List</li>
        </ol>
    </div>

    <!-- Main content -->
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="font-medium text-slate-900">Coupon List</h3>
            <x-ui.button variant="success" :href="routeHelper('coupon/create')">
                <i class="fas fa-plus-circle"></i>
                Add Coupon
            </x-ui.button>
        </div>

        <div class="p-4">
            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Coupon Code</th>
                        <th>Discount Type</th>
                        <th>Charge</th>
                        <th>User Limit</th>
                        <th>Total Limit</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->code }}</td>
                            <td>{{ $data->discount_type }}</td>
                            <td>
                                @if ($data->discount_type == 'percent')
                                    {{ $data->discount . ' %' }}
                                @else
                                    {{ $data->discount }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                @endif
                            </td>
                            <td>{{ $data->limit_per_user }}</td>
                            <td>{{ $data->total_use_limit }}</td>
                            <td>
                                @if ($data->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <x-ui.button variant="primary" size="sm" :href="routeHelper('coupon/' . $data->id)">
                                    <i class="fas fa-eye"></i>
                                </x-ui.button>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('coupon/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('coupon/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>SL</th>
                        <th>Coupon Code</th>
                        <th>Discount Type</th>
                        <th>Charge</th>
                        <th>User Limit</th>
                        <th>Total Limit</th>
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
    <script>
        $(function() {
            $("#example1").DataTable();
        })
    </script>
@endpush

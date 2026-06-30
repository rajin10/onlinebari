@extends('layouts.admin.app')

@section('title', 'Vendor List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush


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
                // "lengthChange": false,
                "paging": false, // Disable pagination
                "info": false, // Hide information element
                "searching": false, // Hide search input
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        })
    </script>
@endpush




@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div>
            <div class="flex flex-wrap items-center justify-between mb-2">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Vendor List</h1>
                </div>
                <div>
                    <ol class="flex items-center gap-1 text-sm text-slate-500">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li class="before:content-['/'] before:mx-1">Vendor List</li>
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
                    <span class="font-semibold text-slate-800">Vendor List</span>
                    <x-ui.button variant="success" size="sm" :href="routeHelper('vendor/create')">
                        <i class="fas fa-plus-circle"></i>
                        Add Vendor
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>pending</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->username }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->phone }}</td>

                            <td>
                                @if ($data->is_approved == 1)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>{{ $data->vendorAccount->amount ?? 0 }}</td>
                            <td>{{ $data->vendorAccount->pending_amount ?? 0 }}</td>
                            <td class="relative">
                                @if ($data->is_approved == 1)
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('user/status/' . $data->id)" title="Disable">
                                        <i class="fas fa-lock-open"></i>
                                    </x-ui.button>
                                @else
                                    <x-ui.button variant="warning" size="sm" :href="routeHelper('user/status/' . $data->id)" title="Active">
                                        <i class="fas fa-lock"></i>
                                    </x-ui.button>
                                @endif
                                <x-ui.button variant="primary" size="sm" :href="route('admin.vendor.product', ['vid' => $data->id])">
                                    <i class="fas fa-store"></i>
                                </x-ui.button>
                                <x-ui.button variant="primary" size="sm" :href="routeHelper('vendor/' . $data->id)">
                                    <i class="fas fa-eye"></i>
                                </x-ui.button>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('vendor/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('vendor/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <x-ui.button variant="info" size="sm" class="mt-2" onclick="action_vn({{ $data->id }})">ACTION</x-ui.button>
                                <div id="action_apply_vn_{{ $data->id }}"
                                    style="display:none;background:var(--primary_color);padding:7px 10px;position:absolute;bottom:-30px;right:100%;width:90%;border-radius:4px;z-index:9999;">
                                    <a style="color:var(--secondary_color);"
                                        href="{{ route('admin.vendor.change_pass_index', ['id' => $data->id]) }}">Change
                                        Password</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @push('js')
                        <script>
                            function action_vn(data_id) {
                                $(`#action_apply_vn_${data_id}`).toggle();
                            }
                        </script>
                    @endpush
                </tbody>
            </x-ui.table>

            <div class="mt-4 text-sm text-slate-600">
                {{ $vendors->firstItem() }} - {{ $vendors->lastItem() }} of {{ $vendors->total() }} results
            </div>
            <nav aria-label="Page navigation example" class="mt-2">
                <ul class="flex flex-wrap gap-1">
                    {{-- First Page Button --}}
                    <li>
                        <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm text-slate-700 hover:bg-slate-50"
                            href="{{ $vendors->url(1) }}">First</a>
                    </li>

                    {{-- Page Numbers --}}
                    @php
                        $totalPages = ceil($vendors->total() / $vendors->perPage());
                        $currentPage = $vendors->currentPage();
                        $middlePage = floor($totalPages / 2);
                    @endphp

                    @if ($totalPages > 3)
                        {{-- Immediate two pages before the first page --}}
                        @for ($i = max($currentPage - 2, 2); $i < $currentPage; $i++)
                            <li>
                                <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm {{ $currentPage == $i ? 'bg-primary text-white border-primary' : 'text-slate-700 hover:bg-slate-50' }}"
                                    href="{{ $vendors->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Current Page --}}
                        <li>
                            <a class="inline-flex items-center px-3 h-9 border border-primary rounded text-sm bg-primary text-white"
                                href="#">{{ $currentPage }}</a>
                        </li>

                        {{-- Immediate two pages after the last page --}}
                        @for ($i = $currentPage + 1; $i <= min($currentPage + 2, $totalPages - 1); $i++)
                            <li>
                                <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm {{ $currentPage == $i ? 'bg-primary text-white border-primary' : 'text-slate-700 hover:bg-slate-50' }}"
                                    href="{{ $vendors->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Last Page Button --}}
                        <li>
                            <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm text-slate-700 hover:bg-slate-50"
                                href="{{ $vendors->url($totalPages) }}">Last</a>
                        </li>
                    @else
                        {{-- Page Numbers if total pages are less than or equal to 3 --}}
                        @for ($i = 2; $i < $totalPages; $i++)
                            <li>
                                <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm {{ $currentPage == $i ? 'bg-primary text-white border-primary' : 'text-slate-700 hover:bg-slate-50' }}"
                                    href="{{ $vendors->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif
                    @if ($vendors->nextPageUrl())
                        <li>
                            <a class="inline-flex items-center px-3 h-9 border border-slate-200 rounded text-sm text-slate-700 hover:bg-slate-50"
                                href="{{ $vendors->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">Next</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

        </x-ui.card>

    </section>

@endsection

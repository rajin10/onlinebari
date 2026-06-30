@extends('layouts.vendor.app')

@section('title', 'Product List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Product List</h1>
            <nav class="text-sm text-slate-500">
                <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
                <span class="mx-1">/</span>
                <span>Product List</span>
            </nav>
        </div>
    </section>

    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span class="font-medium text-slate-900">Product List</span>
                    <x-ui.button variant="primary" :href="routeHelper('product/create')">
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
                                    @if ($data->is_aproved == 1)
                                        <x-ui.badge variant="success">Approved</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger">Unapproved</x-ui.badge>
                                    @endif
                                @else
                                    <x-ui.badge variant="danger">Pending</x-ui.badge>
                                    @if ($data->is_aproved == 1)
                                        <x-ui.badge variant="success">Approved</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger">Unapproved</x-ui.badge>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button variant="primary" size="sm" :href="route('vendor.product.order', $data->id)" title="Order Product">
                                        <i class="fab fa-jedi-order"></i>
                                    </x-ui.button>
                                    @if ($data->is_aproved == 1)
                                        @if ($data->status)
                                            <x-ui.button variant="primary" size="sm" :href="routeHelper('product/status/' . $data->id)" title="Disable">
                                                <i class="fas fa-thumbs-up"></i>
                                            </x-ui.button>
                                        @else
                                            <x-ui.button size="sm" :href="routeHelper('product/status/' . $data->id)" title="Active"
                                                class="bg-warning text-white hover:opacity-90">
                                                <i class="fas fa-thumbs-down"></i>
                                            </x-ui.button>
                                        @endif
                                    @endif
                                    <x-ui.button variant="primary" size="sm" :href="routeHelper('product/' . $data->id)">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <x-ui.button size="sm" :href="routeHelper('product/' . $data->id . '/edit')"
                                        class="bg-info text-white hover:opacity-90">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </x-ui.button>
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

            <div class="mt-3 text-sm text-slate-500">
                {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} results
            </div>

            <nav class="mt-2">
                <ul class="inline-flex gap-1 text-sm">
                    {{-- First Page Button --}}
                    <li>
                        <a class="rounded border border-slate-200 px-3 py-1 hover:bg-slate-100" href="{{ $products->url(1) }}">First</a>
                    </li>

                    {{-- Page Numbers --}}
                    @php
                        $totalPages = ceil($products->total() / $products->perPage());
                        $currentPage = $products->currentPage();
                        $middlePage = floor($totalPages / 2);
                    @endphp

                    @if ($totalPages > 3)
                        {{-- Immediate two pages before the first page --}}
                        @for ($i = max($currentPage - 2, 2); $i < $currentPage; $i++)
                            <li>
                                <a class="rounded border border-slate-200 px-3 py-1 {{ $currentPage == $i ? 'bg-primary text-white' : 'hover:bg-slate-100' }}"
                                    href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Current Page --}}
                        <li>
                            <a class="rounded border border-primary bg-primary px-3 py-1 text-white" href="#">{{ $currentPage }}</a>
                        </li>

                        {{-- Immediate two pages after the last page --}}
                        @for ($i = $currentPage + 1; $i <= min($currentPage + 2, $totalPages - 1); $i++)
                            <li>
                                <a class="rounded border border-slate-200 px-3 py-1 {{ $currentPage == $i ? 'bg-primary text-white' : 'hover:bg-slate-100' }}"
                                    href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Last Page Button --}}
                        <li>
                            <a class="rounded border border-slate-200 px-3 py-1 hover:bg-slate-100" href="{{ $products->url($totalPages) }}">Last</a>
                        </li>
                    @else
                        {{-- Page Numbers if total pages are less than or equal to 3 --}}
                        @for ($i = 2; $i < $totalPages; $i++)
                            <li>
                                <a class="rounded border border-slate-200 px-3 py-1 {{ $currentPage == $i ? 'bg-primary text-white' : 'hover:bg-slate-100' }}"
                                    href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    @endif

                    @if ($products->nextPageUrl())
                        <li>
                            <a class="rounded border border-slate-200 px-3 py-1 hover:bg-slate-100"
                                href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">Next</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

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
    <script src="/assets/plugins/datatables-buttons/js/buttons.colvis.min.js"></script>
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

@extends('layouts.admin.app')

@section('title', 'All Product List')

@push('css')
    <!-- <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">All Product List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">All Product List</li>
            </ol>
        </div>
    </section>

    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span class="text-base font-semibold text-slate-800">All Product List</span>
                    <x-ui.button variant="success" :href="routeHelper('product/create')">
                        <i class="fas fa-plus-circle"></i>
                        Add Product
                    </x-ui.button>
                </div>
            </x-slot:header>

            <form action="{{ route('admin.product.index') }}" method="GET" class="mb-4 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
                <div>
                    <x-ui.input name="title" label="Search Title" type="text" :value="request('title')" placeholder="Enter product title" />
                </div>

                <div>
                    <x-ui.select name="status" label="Status">
                        <option value="" {{ request('status') === null ? 'selected' : '' }}>All</option>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}"
                                {{ (string) request('status') === (string) $key ? 'selected' : '' }}>{{ $value }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div>
                    <x-ui.select name="filter" label="Filter Type">
                        <option value="" {{ request('filter') === null ? 'selected' : '' }}>All</option>
                        <option value="inhouse" {{ request('filter') == 'inhouse' ? 'selected' : '' }}>Inhouse</option>
                        <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="reached" {{ request('filter') == 'reached' ? 'selected' : '' }}>Most Reached</option>
                        <option value="unapproved" {{ request('filter') == 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                        <option value="approved" {{ request('filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </x-ui.select>
                </div>

                <div>
                    <x-ui.select name="brand" label="Brand" class="select2">
                        <option value="" selected>All</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div>
                    <x-ui.select name="category" label="Category" class="select2">
                        <option value="" selected>All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="flex items-end gap-2 pb-4">
                    <x-ui.button type="submit" variant="primary">Filter</x-ui.button>
                    <x-ui.button variant="success" :href="route('admin.product.index', array_merge(request()->all(), ['export' => 'csv']))">
                        Download CSV
                    </x-ui.button>
                </div>
            </form>

            <h3 class="mb-2 text-right text-base font-semibold text-slate-700">Total {{ $products->total() }} results</h3>

            <x-ui.table class="text-center">
                <thead>
                    <tr>
                        <th style="width:5%;">SL</th>
                        <th style="width:10%;">Image</th>
                        <th style="width:19%;">Title</th>
                        <th style="width:9%;" title="Regular Price">Regular Price</th>
                        <th style="width:9%;" title="Discount Price">Discount Price</th>
                        <th style="width:9%;">Stock</th>
                        <th style="width:9%;">Categories</th>
                        <th style="width:10%;">Brand</th>
                        <th style="width:10%;">Status</th>
                        <th style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            <td>
                                <img src="{{ asset('uploads/product/' . $product->image) }}" alt="Product Image"
                                    width="80px">
                            </td>
                            <td>{{ $product->title ?? 'N/A' }}</td>
                            <td>{{ $product->regular_price ?? 'N/A' }}</td>
                            <td>{{ $product->discount_price ?? 'N/A' }}</td>
                            <td>
                                {{ $product->quantity }}
                                @if ($product->quantity > 0)
                                    <x-ui.badge variant="success">Available</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Unavailable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                @foreach ($product->categories as $category)
                                    {{ $category->name ?? '' }}

                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $product->brand->name ?? '' }}</td>
                            <td>
                                @if ($product->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1">
                                    <x-ui.button variant="primary" size="sm" :href="route('admin.product.order', $product->id)" title="Create Order for this Product">
                                        <i class="fas fa-cart-plus"></i>
                                    </x-ui.button>
                                    @if ($product->status)
                                        <x-ui.button variant="success" size="sm" :href="routeHelper('product/status/' . $product->id)" title="Disable">
                                            <i class="fas fa-thumbs-up"></i>
                                        </x-ui.button>
                                    @else
                                        <x-ui.button variant="warning" size="sm" :href="routeHelper('product/status/' . $product->id)" title="Active">
                                            <i class="fas fa-thumbs-down"></i>
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button variant="primary" size="sm" :href="routeHelper('product/' . $product->id)">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    <x-ui.button variant="info" size="sm" :href="routeHelper('product/' . $product->id . '/edit')">
                                        <i class="fas fa-edit"></i>
                                    </x-ui.button>
                                    @if (auth()->user()->desig != 3)
                                        <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $product->id }}" id="deleteData">
                                            <i class="nav-icon fas fa-trash-alt"></i>
                                        </x-ui.button>
                                    @endif
                                    <form id="delete-data-form-{{ $product->id }}"
                                        action="{{ routeHelper('product/' . $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-ui.table>

            <div class="mt-4 flex justify-center">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </x-ui.card>

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
@endpush

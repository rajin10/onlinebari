@extends('layouts.admin.app')

@section('title', 'Incomplete Leads List')

@push('css')
    <style>
        /* animate-pulse uses cubic-bezier; original used linear — keep keyframe for exact parity */
        @keyframes badge-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .badge-new { animation: badge-pulse 2s linear infinite; }
    </style>
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Incomplete Leads List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Incomplete Leads</li>
            </ol>
        </div>
    </section>

    <section class="mb-6">
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-slate-900">Incomplete Lead Tracking</h3>
                    <x-ui.button :href="route('admin.incomplete.leads.export')" variant="success" size="sm">
                        <i class="fas fa-download"></i> Export CSV
                    </x-ui.button>
                </div>
            </x-slot:header>

            {{-- Filter Form --}}
            <form action="{{ route('admin.incomplete.leads.index') }}" method="GET" class="mb-4 grid grid-cols-12 gap-3 items-end">
                <div class="col-span-12 md:col-span-3">
                    <x-ui.input
                        name="keyword"
                        label="Search by Name or Phone"
                        type="text"
                        :value="request('keyword')"
                        placeholder="Name or Phone"
                    />
                </div>

                <div class="col-span-12 md:col-span-2">
                    <x-ui.select name="converted" label="Status">
                        <option value="" selected>All</option>
                        <option value="0" {{ request('converted') === '0' ? 'selected' : '' }}>Active (Not Converted)</option>
                        <option value="1" {{ request('converted') === '1' ? 'selected' : '' }}>Converted</option>
                    </x-ui.select>
                </div>

                <div class="col-span-12 md:col-span-2">
                    <x-ui.input
                        name="from_date"
                        label="From Date"
                        type="date"
                        :value="request('from_date')"
                    />
                </div>

                <div class="col-span-12 md:col-span-2">
                    <x-ui.input
                        name="to_date"
                        label="To Date"
                        type="date"
                        :value="request('to_date')"
                    />
                </div>

                <div class="col-span-12 md:col-span-3 flex gap-2 pb-1">
                    <x-ui.button type="submit" variant="primary">
                        <i class="fas fa-filter"></i> Filter
                    </x-ui.button>
                    <x-ui.button variant="secondary" :href="route('admin.incomplete.leads.index')">
                        <i class="fas fa-redo"></i> Reset
                    </x-ui.button>
                </div>
            </form>

            {{-- Stats Cards --}}
            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                <x-ui.stat-tile
                    variant="info"
                    :value="$stats['total'] ?? 0"
                    label="Total Leads"
                    icon="fas fa-users"
                />
                <x-ui.stat-tile
                    variant="warning"
                    :value="$stats['active'] ?? 0"
                    label="Active (Not Converted)"
                    icon="fas fa-user-clock"
                />
                <x-ui.stat-tile
                    variant="success"
                    :value="$stats['converted'] ?? 0"
                    label="Converted to Orders"
                    icon="fas fa-check-circle"
                />
                <x-ui.stat-tile
                    variant="primary"
                    :value="number_format($stats['conversion_rate'] ?? 0, 1) . '%'"
                    label="Conversion Rate"
                    icon="fas fa-percentage"
                />
            </div>

            <p class="mb-3 text-right text-base font-semibold text-slate-700">Total {{ $leads->total() }} results</p>

            <x-ui.table class="[&_tbody>tr:hover]:bg-slate-100 [&_td]:align-middle">
                <thead>
                    <tr>
                        <th width="50">SL</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Cart Items</th>
                        <th>Subtotal</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $key => $lead)
                        <tr class="{{ !$lead->converted && $lead->last_activity > now()->subHours(1) ? '!bg-amber-100' : '' }}">
                            <td>{{ $leads->firstItem() + $key }}</td>
                            <td>
                                <strong>{{ $lead->name ?? 'N/A' }}</strong>
                                @if (!$lead->converted && $lead->last_activity > now()->subMinutes(30))
                                    <x-ui.badge variant="danger" class="badge-new ml-1">Active Now</x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <a href="tel:{{ $lead->phone }}">{{ $lead->phone ?? 'N/A' }}</a>
                                @if ($lead->phone)
                                    <x-ui.button :href="'tel:' . $lead->phone" variant="success" size="sm" class="ml-1" title="Call Now">
                                        <i class="fas fa-phone"></i>
                                    </x-ui.button>
                                @endif
                            </td>
                            <td>{{ $lead->email ?? 'N/A' }}</td>
                            <td>
                                @if ($lead->cart_data && count($lead->cart_data) > 0)
                                    <div class="max-w-[300px]" x-data="{ open: false }">
                                        <x-ui.button type="button" variant="info" size="sm" @click="open = true">
                                            <i class="fas fa-shopping-cart"></i>
                                            {{ $lead->total_items }} Item(s)
                                        </x-ui.button>

                                        {{-- Cart Modal --}}
                                        <div
                                            x-show="open"
                                            x-cloak
                                            @keydown.escape.window="open = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            id="cartModal{{ $lead->id }}"
                                        >
                                            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                            <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-xl">
                                                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                                                    <h5 class="font-medium text-slate-900">Cart Items - {{ $lead->name }}</h5>
                                                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                                                        <span class="text-xl">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="p-4">
                                                    <table class="w-full text-left text-sm text-slate-700 [&_td]:border [&_td]:border-slate-200 [&_td]:px-3 [&_td]:py-1.5 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:px-3 [&_th]:py-1.5 [&_th]:font-medium">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Qty</th>
                                                                <th>Price</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($lead->cart_data as $item)
                                                                <tr>
                                                                    <td>
                                                                        @if (!empty($item['image']))
                                                                            <img src="{{ asset($item['image']) }}" alt="" width="40" class="mr-2">
                                                                        @endif
                                                                        <strong>{{ $item['product_name'] ?? 'N/A' }}</strong>
                                                                    </td>
                                                                    <td>{{ $item['qty'] ?? 1 }}</td>
                                                                    <td>{{ number_format($item['price'] ?? 0, 2) }} TK</td>
                                                                    <td>{{ number_format($item['total'] ?? 0, 2) }} TK</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="3" class="text-right">Subtotal:</th>
                                                                <th>{{ number_format($lead->subtotal, 2) }} TK</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-500">No items</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($lead->subtotal, 2) }} TK</strong>
                            </td>
                            <td>
                                <small>
                                    {{ $lead->last_activity ? $lead->last_activity->format('d M Y') : 'N/A' }}<br>
                                    <span class="text-slate-500">{{ $lead->last_activity ? $lead->last_activity->format('h:i A') : '' }}</span>
                                    @if ($lead->last_activity)
                                        <br><x-ui.badge variant="neutral">{{ $lead->last_activity->diffForHumans() }}</x-ui.badge>
                                    @endif
                                </small>
                            </td>
                            <td>
                                @if ($lead->converted)
                                    <x-ui.badge variant="success">
                                        <i class="fas fa-check-circle"></i> Converted
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge variant="warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </x-ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="inline-flex gap-1" x-data="{ open: false }">
                                    <x-ui.button type="button" variant="info" size="sm" @click="open = true" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>

                                    @if (!$lead->converted)
                                        <x-ui.button :href="'tel:' . $lead->phone" variant="success" size="sm" title="Call Customer">
                                            <i class="fas fa-phone"></i>
                                        </x-ui.button>

                                        <form action="{{ route('admin.incomplete.leads.delete', $lead->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </x-ui.button>
                                        </form>
                                    @endif

                                    {{-- Detail Modal --}}
                                    <div
                                        x-show="open"
                                        x-cloak
                                        @keydown.escape.window="open = false"
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                        id="detailModal{{ $lead->id }}"
                                    >
                                        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                                        <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-xl">
                                            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                                                <h5 class="font-medium text-slate-900">Lead Details - {{ $lead->name }}</h5>
                                                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                                                    <span class="text-xl">&times;</span>
                                                </button>
                                            </div>
                                            <div class="p-4">
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                    <div>
                                                        <h6 class="mb-2 text-sm font-semibold text-slate-700">Customer Information</h6>
                                                        <table class="w-full text-left text-sm text-slate-700 [&_td]:px-2 [&_td]:py-1 [&_th]:px-2 [&_th]:py-1 [&_th]:font-medium">
                                                            <tr>
                                                                <th width="120">Name:</th>
                                                                <td>{{ $lead->name ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Phone:</th>
                                                                <td>{{ $lead->phone ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Email:</th>
                                                                <td>{{ $lead->email ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Address:</th>
                                                                <td>{{ $lead->address ?? 'N/A' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-2 text-sm font-semibold text-slate-700">Lead Information</h6>
                                                        <table class="w-full text-left text-sm text-slate-700 [&_td]:px-2 [&_td]:py-1 [&_th]:px-2 [&_th]:py-1 [&_th]:font-medium">
                                                            <tr>
                                                                <th width="140">IP Address:</th>
                                                                <td>{{ $lead->ip_address ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Page URL:</th>
                                                                <td><small>{{ $lead->page_url ?? 'N/A' }}</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Session ID:</th>
                                                                <td><small>{{ $lead->session_id }}</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Created At:</th>
                                                                <td>{{ $lead->created_at->format('d M Y h:i A') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Last Activity:</th>
                                                                <td>{{ $lead->last_activity ? $lead->last_activity->format('d M Y h:i A') : 'N/A' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                @if ($lead->cart_data && count($lead->cart_data) > 0)
                                                    <hr class="my-3 border-slate-200">
                                                    <h6 class="mb-2 text-sm font-semibold text-slate-700">Cart Items</h6>
                                                    <table class="w-full text-left text-sm text-slate-700 [&_td]:border [&_td]:border-slate-200 [&_td]:px-3 [&_td]:py-1.5 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:px-3 [&_th]:py-1.5 [&_th]:font-medium">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th width="80">Qty</th>
                                                                <th width="100">Price</th>
                                                                <th width="100">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($lead->cart_data as $item)
                                                                <tr>
                                                                    <td>{{ $item['product_name'] ?? 'N/A' }}</td>
                                                                    <td>{{ $item['qty'] ?? 1 }}</td>
                                                                    <td>{{ number_format($item['price'] ?? 0, 2) }} TK</td>
                                                                    <td>{{ number_format($item['total'] ?? 0, 2) }} TK</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="3" class="text-right">Subtotal:</th>
                                                                <th>{{ number_format($lead->subtotal, 2) }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                @endif
                                            </div>
                                            <div class="flex justify-end gap-2 border-t border-slate-200 px-4 py-3">
                                                @if (!$lead->converted && $lead->phone)
                                                    <x-ui.button :href="'tel:' . $lead->phone" variant="success">
                                                        <i class="fas fa-phone"></i> Call Now
                                                    </x-ui.button>
                                                @endif
                                                <x-ui.button type="button" variant="secondary" @click="open = false">Close</x-ui.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <p class="py-4 text-slate-500">
                                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                    No incomplete leads found
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-ui.table>

            <div class="mt-4 flex justify-center">
                {{ $leads->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </x-ui.card>
    </section>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Auto refresh every 2 minutes for active leads
            @if (request('converted') === '0' || !request('converted'))
                setInterval(function() {
                    location.reload();
                }, 120000); // 2 minutes
            @endif

            // Highlight new leads
            $('.badge-new').each(function() {
                $(this).closest('tr').addClass('!bg-amber-100');
            });
        });
    </script>
@endpush

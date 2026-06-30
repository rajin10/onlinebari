@extends('layouts.admin.app')

@section('title', 'Coupon Information')

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Coupon Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Show Coupon</li>
            </ol>
        </div>
    </section>

    <section class="mb-6">

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">Coupon Information</h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" size="sm" :href="routeHelper('coupon/' . $coupon->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" size="sm" :href="routeHelper('coupon')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>
            <div class="p-4">
                <x-ui.table>
                    <tbody>
                        <tr>
                            <th width="20%">Coupon Code</th>
                            <td width="80%">{{ $coupon->code }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Discount Type</th>
                            <td width="85%">{{ $coupon->discount_type }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Discount Amount/Percent</th>
                            <td width="85%">
                                @if ($coupon->discount_type == 'percent')
                                    {{ $coupon->discount . ' %' }}
                                @else
                                    {{ $coupon->discount }} {{ setting('CURRENCY_CODE_MIN') ?? 'TK' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th width="15%">Use Limit Per User</th>
                            <td width="85%">{{ $coupon->limit_per_user }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Total Use Limit</th>
                            <td width="85%">{{ $coupon->total_use_limit }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Expire Date</th>
                            <td width="85%">{{ date('d M Y', strtotime($coupon->expire_date)) }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Description</th>
                            <td width="85%">{{ $coupon->description }}</td>
                        </tr>
                        <tr>
                            <th width="15%">Status</th>
                            <td width="85%">
                                @if ($coupon->status)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Disable</x-ui.badge>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </x-ui.table>
            </div>
        </div>

    </section>

@endsection

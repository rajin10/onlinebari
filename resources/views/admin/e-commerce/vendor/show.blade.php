@extends('layouts.admin.app')

@section('title', 'Vendor Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Vendor Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">Vendor Product</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

            {{-- Card header --}}
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">Vendor Details</h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" :href="routeHelper('vendor/' . $vendor->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('vendor')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            {{-- Card body --}}
            <div class="p-4">
                <x-ui.table>
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $vendor->name }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ $vendor->username }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $vendor->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $vendor->phone }}</td>
                        </tr>
                        <tr>
                            <th>Shop Name</th>
                            <td>{{ $vendor->shop_info->name }}</td>
                        </tr>
                        <tr>
                            <th>Shop Address</th>
                            <td>{{ $vendor->shop_info->address }}</td>
                        </tr>
                        <tr>
                            <th>Shop Url</th>
                            <td>{{ $vendor->shop_info->url }}</td>
                        </tr>
                        <tr>
                            <th>Bank Account</th>
                            <td>{{ $vendor->shop_info->bank_account }}</td>
                        </tr>
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $vendor->shop_info->bank_name }}</td>
                        </tr>
                        <tr>
                            <th>Holder Name</th>
                            <td>{{ $vendor->shop_info->holder_name }}</td>
                        </tr>
                        <tr>
                            <th>Branch Name</th>
                            <td>{{ $vendor->shop_info->branch_name }}</td>
                        </tr>
                        <tr>
                            <th>Routing</th>
                            <td>{{ $vendor->shop_info->routing }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $vendor->shop_info->description }}</td>
                        </tr>
                        <tr>
                            <th>Profile</th>
                            <td>
                                <img src="/uploads/shop/profile/{{ $vendor->shop_info->profile }}" alt="Profile"
                                    width="100px">
                            </td>
                        </tr>
                        <tr>
                            <th>Cover Photo</th>
                            <td>
                                <img src="/uploads/shop/cover/{{ $vendor->shop_info->cover_photo }}" alt="Profile"
                                    width="100px">
                            </td>
                        </tr>
                        <tr>
                            <th>Nid Photo</th>
                            <td>
                                <img src="/uploads/shop/nid/{{ $vendor->shop_info->nid }}" alt="Profile" width="100px">
                            </td>
                        </tr>
                        <tr>
                            <th>Trade Photo</th>
                            <td>
                                <img src="/uploads/shop/trade/{{ $vendor->shop_info->trade }}" alt="Profile"
                                    width="100px">
                            </td>
                        </tr>
                    </tbody>
                </x-ui.table>
            </div>

        </div>

    </section>

@endsection

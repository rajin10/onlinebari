@extends('layouts.admin.app')

@section('title', 'Information')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                {{ $customer->desig != '' ? 'Staff' : 'Customer' }} Information
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Details</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            {{-- Card header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">
                    {{ $customer->desig != '' ? 'Staff' : 'Customer' }} Details
                </h3>
                <div class="flex items-center gap-2">
                    <x-ui.button variant="info" :href="routeHelper('customer/' . $customer->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('customer')">
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
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ $customer->username }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{ $customer->customer_info->country }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $customer->customer_info->city }}</td>
                        </tr>
                        <tr>
                            <th>Street</th>
                            <td>{{ $customer->customer_info->street }}</td>
                        </tr>
                        <tr>
                            <th>Post Code</th>
                            <td>{{ $customer->customer_info->post_code }}</td>
                        </tr>
                    </tbody>
                </x-ui.table>
            </div>
        </div>

    </section>

@endsection

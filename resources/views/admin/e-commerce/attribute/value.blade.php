@extends('layouts.admin.app')

@section('title')
    Add attribute Value
@endsection

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Add attribute value</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Add attribute value</li>
            </ol>
        </div>
    </section>

    {{-- Add form --}}
    <section class="mb-6">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800">Add New attribute value</h3>
                    </div>
                </div>

                <form action="{{ route('admin.attribute.value.store') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $attribute->id }}" name="att">

                    <div class="p-4">
                        <div class="mb-4">
                            <x-ui.input
                                name="name"
                                label="Value Name:"
                                type="text"
                                placeholder="Write attribute value name"
                                :value="old('name')"
                                required
                                autocomplete="off"
                            />
                        </div>
                    </div>

                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Attribute values list --}}
    <section>
        <x-ui.card>
            <x-slot:header>
                <h3 class="font-semibold text-slate-800">attribute List</h3>
            </x-slot:header>

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($values as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->name }}</td>
                            <td class="space-x-1">
                                <x-ui.button variant="info" size="sm" :href="routeHelper('attribute/value/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('attribute/value/delete/' . $data->id) }}" method="POST">
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
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
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
    <script>
        $(function() {
            $("#example1").DataTable();
        })
    </script>
@endpush

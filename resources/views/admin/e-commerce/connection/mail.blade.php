@extends('layouts.admin.app')

@section('title', 'mails List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">mails List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mr-1 before:content-['/']">mails List</li>
            </ol>
        </div>
    </section>

    <section>

        <x-ui.card>

            @if (!empty(Session::get('massage2')))
                <x-ui.alert variant="success" class="mb-4 text-center">
                    {{ Session::get('massage2') }}
                </x-ui.alert>
            @endif

            <x-ui.table id="example1">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mails as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->title }}</td>

                            <td class="flex gap-1">

                                <x-ui.button variant="primary" size="sm" :href="route('admin.mail.show', ['id' => $data->id])">
                                    <i class="nav-icon fas fa-eye"></i>
                                </x-ui.button>

                                <x-ui.button variant="danger" size="sm" :href="route('admin.mail.delete', ['id' => $data->id])">
                                    <i class="nav-icon fas fa-trash-alt"></i>
                                </x-ui.button>

                            </td>
                        </tr>
                    @endforeach

                </tbody>

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

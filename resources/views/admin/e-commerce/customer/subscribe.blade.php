@extends('layouts.admin.app')

@section('title', 'Subscriber List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')

    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Subscriber List</h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a></li>
            <li class="before:mx-1 before:content-['/']">Subscriber List</li>
        </ol>
    </div>

    <x-ui.card>
        <x-slot:header>Subscriber List</x-slot:header>

        <x-ui.table id="example1">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscribes as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </x-ui.table>
    </x-ui.card>

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

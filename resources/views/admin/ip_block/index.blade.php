@extends('layouts.admin.app')
@section('title', 'IP Block List')
@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">IP Block Management</h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Home</a></li>
            <li>/</li>
            <li class="text-slate-700">IP Block</li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-4">
            <x-ui.card header="Block New IP">
                <form action="{{ route('admin.ip_block.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-ui.input name="ip_address" label="IP Address" type="text"
                            placeholder="Enter IP Address" required />
                    </div>
                    <div class="mb-4">
                        <x-ui.textarea name="reason" label="Reason (Optional)"
                            placeholder="Reason for blocking" />
                    </div>
                    <x-slot:footer>
                        <x-ui.button type="submit" variant="primary">Block IP</x-ui.button>
                    </x-slot:footer>
                </form>
            </x-ui.card>
        </div>
        <div class="col-span-12 md:col-span-8">
            <x-ui.card header="Blocked IP List">
                <x-ui.table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>IP Address</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ipBlocks as $block)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $block->ip_address }}</td>
                                <td>{{ $block->reason }}</td>
                                <td>{{ $block->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <form action="{{ route('admin.ip_block.destroy', $block->id) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger" size="sm">Unblock</x-ui.button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-ui.table>
                <div class="mt-3">
                    {{ $ipBlocks->links() }}
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection

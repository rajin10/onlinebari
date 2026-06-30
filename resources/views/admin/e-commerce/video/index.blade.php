@extends('layouts.admin.app')

@section('content')
    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <span>Videos</span>
                <x-ui.button :href="route('admin.video.create')" size="sm">Add Video</x-ui.button>
            </div>
        </x-slot:header>

        <x-ui.table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($videos as $video)
                    <tr>
                        <td>{{ $video->title }}</td>
                        <td>{{ $video->status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <x-ui.button :href="route('admin.video.edit', $video->id)" variant="warning" size="sm">Edit</x-ui.button>
                            <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST"
                                style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="danger" size="sm">Delete</x-ui.button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-ui.table>
    </x-ui.card>
@endsection

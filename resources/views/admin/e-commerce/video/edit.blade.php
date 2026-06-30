@extends('layouts.admin.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="text-base font-semibold text-slate-800">Edit Video</h3>
            <x-ui.button variant="secondary" size="sm" :href="route('admin.video.index')">Back</x-ui.button>
        </div>

        <form action="{{ route('admin.video.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4 p-4">
                <x-ui.input name="title" label="Title" :value="$video->title" />

                <x-ui.textarea name="description" label="Description" :rows="3">{{ $video->description }}</x-ui.textarea>

                <x-ui.input name="button_text" label="Button Text" :value="$video->button_text" />

                <x-ui.input name="button_url" label="Button URL" :value="$video->button_url" />

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Change Video</label>
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">

                    @if ($video->video)
                        <video width="220" controls class="mt-2">
                            <source src="{{ asset('storage/' . $video->video) }}">
                        </video>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Change Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">

                    @if ($video->thumbnail)
                        <img src="{{ asset('storage/' . $video->thumbnail) }}" width="180" class="mt-2">
                    @endif
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
                        <input type="checkbox" name="status" {{ $video->status ? 'checked' : '' }}
                            class="rounded border-slate-300 text-primary focus:ring-primary">
                        Active
                    </label>
                </div>
            </div>

            <div class="border-t border-slate-200 px-4 py-3">
                <x-ui.button type="submit" variant="primary">Update Video</x-ui.button>
            </div>
        </form>
    </div>
@endsection

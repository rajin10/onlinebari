@extends('layouts.admin.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="font-medium text-slate-900">Add Video</h3>
            <x-ui.button variant="secondary" size="sm" :href="route('admin.video.index')">Back</x-ui.button>
        </div>

        <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-4">
                <div class="mb-4">
                    <x-ui.input name="title" label="Title" />
                </div>

                <div class="mb-4">
                    <x-ui.textarea name="description" label="Description" :rows="3" />
                </div>

                <div class="mb-4">
                    <x-ui.input name="button_text" label="Button Text" />
                </div>

                <div class="mb-4">
                    <x-ui.input name="button_url" label="Button URL" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Video File</label>
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Thumbnail Image</label>
                    <input type="file" name="thumbnail" accept="image/*"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="status" checked class="rounded border-slate-300"> Active
                    </label>
                </div>
            </div>

            <div class="border-t border-slate-200 px-4 py-3">
                <x-ui.button type="submit" variant="primary">Save Video</x-ui.button>
            </div>
        </form>
    </div>
@endsection

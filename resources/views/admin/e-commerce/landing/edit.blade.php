@php
    use App\Models\LandingPageContent;

    $fields = collect($config['fields']);
    // preserveKeys=true so each grouped field keeps its string key (hero_image, …);
    // that key is the input `name`, so the controller receives the right fields.
    $sections = $fields->groupBy('section', true);
@endphp

@extends('layouts.admin.app')

@section('title', $config['label'] . ' — Content')

@section('content')
    <section class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">{{ $config['label'] }}</h1>
            <p class="text-sm text-slate-500">Manage the images and video shown on this landing page. Changes go live immediately.</p>
        </div>
        <a href="{{ route($config['route']) }}" target="_blank"
            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            <i class="bx bx-link-external"></i> View page
        </a>
    </section>

    <section>
        <div class="flex justify-center">
            <div class="w-full md:w-2/3">
                <form action="{{ route('admin.landing.update', $page) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @foreach ($sections as $sectionName => $sectionFields)
                        <div class="mb-4 rounded-lg border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                                {{ $sectionName }}
                            </div>

                            <div class="space-y-6 p-4">
                                @foreach ($sectionFields as $key => $field)
                                    @php
                                        $value = $content[$key] ?? null;
                                    @endphp

                                    {{-- ---------------------------------------- image slot --}}
                                    @if ($field['type'] === 'image')
                                        @php $imageUrl = LandingPageContent::imageUrl($value); @endphp
                                        <div>
                                            <label for="{{ $key }}" class="block text-sm font-medium text-slate-700">
                                                {{ $field['label'] }}
                                            </label>
                                            <p class="mb-2 text-xs text-slate-500">{{ $field['help'] }}</p>

                                            <div class="flex items-start gap-4">
                                                <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                                    <img id="preview-{{ $key }}"
                                                        src="{{ $imageUrl }}"
                                                        alt=""
                                                        class="h-full w-full object-cover {{ $imageUrl ? '' : 'hidden' }}">
                                                    <span id="preview-empty-{{ $key }}"
                                                        class="px-2 text-center text-[11px] text-slate-400 {{ $imageUrl ? 'hidden' : '' }}">
                                                        No image yet
                                                    </span>
                                                </div>

                                                <div class="flex-1">
                                                    <input type="file" name="{{ $key }}" id="{{ $key }}"
                                                        accept="image/jpeg,image/png,image/webp"
                                                        data-preview="preview-{{ $key }}"
                                                        data-preview-empty="preview-empty-{{ $key }}"
                                                        class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-slate-800 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-700">
                                                    <p class="mt-1 text-xs text-slate-400">JPG, PNG or WEBP · up to 4 MB. Leave empty to keep the current image.</p>

                                                    @error($key)
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror

                                                    @if ($imageUrl)
                                                        <button type="submit"
                                                            form="delete-{{ $key }}"
                                                            onclick="return confirm('Remove this image?');"
                                                            class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-red-600 hover:text-red-700">
                                                            <i class="bx bx-trash"></i> Remove image
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    {{-- ---------------------------------------- youtube slot --}}
                                    @elseif ($field['type'] === 'youtube_url')
                                        @php $videoId = LandingPageContent::youtubeId($value); @endphp
                                        <div>
                                            <label for="{{ $key }}" class="block text-sm font-medium text-slate-700">
                                                {{ $field['label'] }}
                                            </label>
                                            <p class="mb-2 text-xs text-slate-500">{{ $field['help'] }}</p>

                                            <input type="url" name="{{ $key }}" id="{{ $key }}"
                                                value="{{ old($key, $value) }}"
                                                placeholder="https://www.youtube.com/watch?v=..."
                                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">

                                            @error($key)
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror

                                            <div class="mt-3">
                                                <p class="mb-1 text-xs font-medium text-slate-500">Live preview</p>
                                                <div id="yt-preview-{{ $key }}"
                                                    class="aspect-video w-full max-w-md overflow-hidden rounded-lg border border-slate-200 bg-slate-50 {{ $videoId ? '' : 'hidden' }}">
                                                    @if ($videoId)
                                                        <iframe class="h-full w-full" src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
                                                            title="Video preview" allowfullscreen></iframe>
                                                    @endif
                                                </div>
                                                <p id="yt-empty-{{ $key }}"
                                                    class="text-xs text-slate-400 {{ $videoId ? 'hidden' : '' }}">
                                                    Paste a valid YouTube URL and save to see the embed here.
                                                </p>
                                            </div>
                                        </div>

                                    {{-- ---------------------------------------- plain text slot --}}
                                    @elseif ($field['type'] === 'text')
                                        <div>
                                            <label for="{{ $key }}" class="block text-sm font-medium text-slate-700">
                                                {{ $field['label'] }}
                                            </label>
                                            <p class="mb-2 text-xs text-slate-500">{{ $field['help'] }}</p>

                                            <input type="text" name="{{ $key }}" id="{{ $key }}"
                                                value="{{ old($key, $value ?? ($field['default'] ?? '')) }}"
                                                placeholder="{{ $field['default'] ?? '' }}"
                                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">

                                            @error($key)
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end">
                        <x-ui.button type="submit" variant="primary">Save changes</x-ui.button>
                    </div>
                </form>

                {{-- Standalone delete forms (kept outside the main form; triggered via the `form` attribute). --}}
                @foreach ($fields as $key => $field)
                    @if ($field['type'] === 'image' && ! empty($content[$key]))
                        <form id="delete-{{ $key }}" action="{{ route('admin.landing.image.delete', [$page, $key]) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        // Instant thumbnail preview for a freshly chosen image (before upload).
        document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) return;
                var img = document.getElementById(input.dataset.preview);
                var empty = document.getElementById(input.dataset.previewEmpty);
                var url = URL.createObjectURL(file);
                img.src = url;
                img.classList.remove('hidden');
                if (empty) empty.classList.add('hidden');
            });
        });

        // Live YouTube embed preview as the admin types.
        document.querySelectorAll('input[type="url"]').forEach(function (input) {
            var box = document.getElementById('yt-preview-' + input.id);
            var empty = document.getElementById('yt-empty-' + input.id);
            if (!box) return;

            function extractId(url) {
                var m = url.match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
                return m ? m[1] : null;
            }

            input.addEventListener('input', function () {
                var id = extractId(input.value.trim());
                if (id) {
                    box.innerHTML = '<iframe class="h-full w-full" src="https://www.youtube-nocookie.com/embed/' + id +
                        '" title="Video preview" allowfullscreen></iframe>';
                    box.classList.remove('hidden');
                    if (empty) empty.classList.add('hidden');
                } else {
                    box.innerHTML = '';
                    box.classList.add('hidden');
                    if (empty) empty.classList.remove('hidden');
                }
            });
        });
    </script>
@endpush

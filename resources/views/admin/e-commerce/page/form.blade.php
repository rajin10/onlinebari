@extends('layouts.admin.app')

@section('title', 'Ticket List')
@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/plugins/file-uploader/image-uploader.min.css">
@endpush


@section('content')

    <!-- Main content -->
    <section class="mb-6">
        @isset($page)
            <form action="{{ route('admin.page.update') }}" method="POST">
                <input type="hidden" value="{{ $page->id }}" name="id">
            @else
                <form action="{{ route('admin.page.make') }}" method="POST">
                    @endif
                    @csrf
                    <x-ui.card>
                        <x-slot:footer>
                            <x-ui.button type="submit" variant="primary">
                                <i class="fas fa-arrow-circle-up"></i>
                                Create
                            </x-ui.button>
                        </x-slot:footer>

                        <div class="mb-4">
                            <x-ui.input name="name" label="Name:" :value="$page->name ?? old('name')" />
                        </div>

                        <div class="mb-4">
                            <x-ui.input name="title" label="Title:" :value="$page->title ?? old('title')" />
                        </div>

                        <div class="mb-4">
                            <label for="full_description" class="block text-sm font-medium text-slate-700 mb-1">Description:</label>
                            <textarea name="body" id="full_description" cols="5" placeholder="Write size description"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $page->body ?? old('body') }}</textarea>
                        </div>

                        <x-ui.select name="position" label="Position:">
                            <option @isset($page) {{ $page->position == 0 ? 'selected' : '' }} @endisset value="0">Nav</option>
                            <option @isset($page){{ $page->position == 1 ? 'selected' : '' }} @endisset value="1">bottom</option>
                            <option @isset($page){{ $page->position == 2 ? 'selected' : '' }} @endisset value="2">Both</option>
                        </x-ui.select>

                        <x-ui.select name="status" label="Status:">
                            <option @isset($page){{ $page->status == 0 ? 'selected' : '' }} @endisset value="1">Active</option>
                            <option @isset($page) {{ $page->status == 1 ? 'selected' : '' }} @endisset value="0">Deactive</option>
                        </x-ui.select>
                    </x-ui.card>
                </form>


    </section>
    <!-- /.content -->

@endsection

@push('js')
    <!-- Select2 -->
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>

    <script type="text/javascript" src="/assets/plugins/file-uploader/image-uploader.min.js"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.dropify').dropify();
            $('#full_description').summernote();
            // $('.input-images-1').imageUploader();

        });
    </script>
@endpush

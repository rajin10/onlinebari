@extends('layouts.admin.app')

@section('title')
    @isset($author)
        Edit Author
    @else
        Add Author
    @endisset
@endsection
@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($author)
                    Edit author
                @else
                    Add author
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($author)
                        Edit author
                    @else
                        Add author
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
            @endforeach
        @endif

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-slate-900">
                        @isset($author)
                            Edit Author
                        @else
                            Add New Author
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('author')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            <form action="{{ route('admin.author.update', $author->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <x-ui.input
                                name="name"
                                label="Name:"
                                type="text"
                                placeholder="Write author name"
                                :value="$author->name ?? old('name')"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label for="profile" class="block text-sm font-medium text-slate-700 mb-1">Profile</label>
                            <img width="50" src="{{ asset('/') }}/uploads/admin/{{ $author->img }}" class="mb-2">
                            <input
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                type="file"
                                name="profile"
                                id="profile"
                            >
                            @error('profile')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-slate-700 mb-1">Bio:</label>
                            <textarea
                                name="bio"
                                id="bio"
                                rows="3"
                                placeholder="Write   bio"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('bio') border-danger @enderror"
                            >{{ $author->bio ?? old('bio') }}</textarea>
                            @error('bio')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button type="submit" variant="primary">
                        @isset($author)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('js')
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#bio').summernote()
        })
    </script>
@endpush

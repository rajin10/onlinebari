@extends('layouts.admin.app')

@section('title')
    @isset($staff)
        Edit Author
    @else
        Add Author
    @endisset
@endsection
@push('css')
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($staff)
                    Edit staff
                @else
                    Add staff
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($staff)
                        Edit staff
                    @else
                        Add staff
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
            @endforeach
        @endif

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            {{-- Card header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">
                    @isset($staff)
                        Edit Author
                    @else
                        Add New Author
                    @endisset
                </h3>
                <x-ui.button variant="danger" :href="routeHelper('staff')">
                    <i class="fas fa-long-arrow-alt-left"></i>
                    Back to List
                </x-ui.button>
            </div>

            <form action="{{ isset($staff) ? routeHelper('staff/' . $staff->id) : route('admin.author.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Card body --}}
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="mb-4">
                            <x-ui.input name="name" label="Name:" type="text"
                                placeholder="Write staff name"
                                :value="$staff->name ?? old('name')"
                                required />
                        </div>
                        <div class="mb-4">
                            <label for="profile" class="block text-sm font-medium text-slate-700 mb-1">Profile</label>
                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                type="file" name="profile" id="profile" required>
                            @error('profile')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4 md:col-span-2">
                            <x-ui.textarea name="bio" label="Bio:" rows="3"
                                placeholder="Write bio">{{ $product->bio ?? old('bio') }}</x-ui.textarea>
                        </div>
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button type="submit" variant="primary">
                        @isset($staff)
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

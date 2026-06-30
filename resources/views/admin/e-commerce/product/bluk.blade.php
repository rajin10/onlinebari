@extends('layouts.admin.app')

@section('title', 'docs')
@push('css')
    <!-- Select2 -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="">
            <div class="mb-2 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-800">Product List</h1>
                <ol class="flex items-center gap-1 text-sm text-slate-500">
                    <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                    <li class="before:mx-1 before:content-['/']">Product List</li>
                </ol>
            </div>
        </div>
    </section>
    <section>

        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Product Export/impot</h3>
                    <x-ui.button variant="success" :href="route('admin.product.export')">
                        <i class="fas fa-file-export"></i> Export
                    </x-ui.button>
                </div>
            </x-slot:header>

            <form method="post" action="{{ route('admin.product.import') }}" enctype="multipart/form-data">
                @csrf
                <input class="dropify" type="file" name="product">
                <br>
                <x-ui.button type="submit" variant="success" class="mt-2">Import</x-ui.button>
            </form>
        </x-ui.card>
    </section>

@endsection
@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush

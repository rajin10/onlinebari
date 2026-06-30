@extends('layouts.admin.app')

@section('title', 'docs')


@section('content')
    <div class="container">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($images as $img)
                <div class="bg-white text-center">
                    <img src="{{ asset('uploads/product/' . $img->name) }}"
                         class="w-full h-[230px] object-contain bg-white p-[10px] rounded-[5px]">
                    <a href="{{ routeHelper('product/' . $img->product_id . '/edit') }}">edit or delete</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

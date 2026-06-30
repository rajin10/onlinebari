@extends('layouts.frontend.app')

@push('meta')
    <meta name='description' content="Cart Products"/>
    <meta name='keywords' content="@foreach(\App\Models\Tag::all() as $tag){{$tag->name.', '}}@endforeach" />
@endpush

@section('title', 'Compare Products')

@section('content')

    <div class="checkout-right">
        <div class="container w-full my-5 mx-auto border border-[#ddd] p-5 box-border">
            <table class="w-full border-collapse text-center">
                <a href="{{ route('compare.clear') }}" class="btn btn-danger mb-2 float-right">Clear compare</a>
                <!-- Table Header -->
                <thead>
                    <tr>
                        <th class="p-[10px] border border-[#ddd] bg-[#f7f7f7] font-bold">Product</th>
                        @foreach(session('compare', []) as $product)
                            <th class="p-[10px] border border-[#ddd] bg-[#f7f7f7] font-bold">{{ $product['title'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <!-- Product Images -->
                    <tr>
                        <td class="p-[10px] border border-[#ddd] font-bold">Image</td>
                        @foreach(session('compare', []) as $product)
                            <td class="p-[10px] border border-[#ddd] w-1/5 h-auto">
                                <img src="{{ asset('uploads/product/' . $product['image']) }}" alt="{{ $product['title'] }}" class="w-full h-auto">
                            </td>
                        @endforeach
                    </tr>
                    <!-- Prices -->
                    <tr>
                        <td class="p-[10px] border border-[#ddd] font-bold">Price</td>
                        @foreach(session('compare', []) as $product)
                            <td class="p-[10px] border border-[#ddd]">${{ number_format($product['discount_price'], 2) }}</td>
                        @endforeach
                    </tr>
                    <!-- Regular Price -->
                    <tr>
                        <td class="p-[10px] border border-[#ddd] font-bold">Regular Price</td>
                        @foreach(session('compare', []) as $product)
                            <td class="p-[10px] border border-[#ddd]">${{ number_format($product['regular_price'], 2) }}</td>
                        @endforeach
                    </tr>
                    <!-- Short Description -->
                    <tr>
                        <td class="p-[10px] border border-[#ddd] font-bold">Description</td>
                        @foreach(session('compare', []) as $product)
                            <td class="p-[10px] border border-[#ddd]">{!! $product['short_description'] !!}</td>
                        @endforeach
                    </tr>
                    <!-- SKU -->
                    <tr>
                        <td class="p-[10px] border border-[#ddd] font-bold">SKU</td>
                        @foreach(session('compare', []) as $product)
                            <td class="p-[10px] border border-[#ddd]">{{ $product['sku'] }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('layouts.vendor.app')

@section('title', 'Product Information')

@section('content')

    {{-- Page header --}}
    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">Product Information</h1>
        <nav class="text-sm text-slate-500">
            <a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a>
            <span class="mx-1">/</span>
            <span>Show Product</span>
        </nav>
    </div>

    {{-- Main card --}}
    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <span class="text-base font-semibold text-slate-800">Product Details</span>
                <div class="flex gap-2">
                    <x-ui.button variant="secondary" :href="routeHelper('product/' . $product->id . '/edit')">
                        <i class="fas fa-edit"></i>
                        Edit
                    </x-ui.button>
                    <x-ui.button variant="danger" :href="routeHelper('product')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>

        {{-- Product overview: image + details --}}
        <div class="flex flex-col gap-6 sm:flex-row">

            {{-- Left: image gallery --}}
            <div class="w-full sm:w-1/2">
                <h3 class="mb-2 block text-lg font-semibold sm:hidden">{{ $product->title }}</h3>
                <div class="mb-3">
                    <img src="{{ asset('uploads/product/' . $product->image) }}"
                         class="h-auto w-full max-w-full rounded object-contain"
                         alt="Product Image">
                </div>
                <div class="flex flex-wrap gap-2">
                    <div class="h-16 w-16 cursor-pointer overflow-hidden rounded border-2 border-primary">
                        <img src="{{ asset('uploads/product/' . $product->image) }}"
                             class="h-full w-full object-cover"
                             alt="Product Image">
                    </div>
                    @foreach ($product->images as $image)
                        <div class="h-16 w-16 cursor-pointer overflow-hidden rounded border-2 border-primary">
                            <img src="{{ asset('uploads/product/' . $image->name) }}"
                                 class="h-full w-full object-cover"
                                 alt="Product Image">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: product info --}}
            <div class="w-full sm:w-1/2">
                <h3 class="my-3 text-xl font-semibold text-slate-800">{{ $product->title }}</h3>
                <p class="text-slate-600">{{ $product->short_description }}</p>

                <hr class="my-4 border-slate-200">

                {{-- Colors --}}
                <h4 class="mb-2 font-semibold text-slate-700">Available Colors</h4>
                <div class="flex flex-wrap gap-2" data-toggle="buttons">
                    @foreach ($colors_product as $color)
                        <label class="cursor-pointer rounded border border-slate-300 bg-white px-3 py-2 text-center text-sm text-slate-700 shadow-sm">
                            {{ $color->name }}
                            <br>
                            <i class="fas fa-circle fa-2x" style="color: {{ $color->code }}"></i>
                            <br>
                            Price:{{ $color->price }}---Qnty:{{ $color->qnty }}
                        </label>
                    @endforeach
                </div>

                {{-- Attributes --}}
                @foreach ($attributes as $attribute)
                    <h4 class="mb-2 mt-4 font-semibold text-slate-700">Available {{ $attribute->name }}</h4>
                    <div class="flex flex-wrap gap-2" data-toggle="buttons">
                        <?php
                        $attribute_prouct = DB::table('attribute_product')->select('*')->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')->addselect('attribute_values.name as vName')->addselect('attribute_product.id as vid')->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')->where('attribute_product.product_id', $product->id)->where('attributes.id', $attribute->id)->get();
                        ?>
                        @foreach ($attribute_prouct as $pro_color)
                            <label class="cursor-pointer rounded border border-slate-300 bg-white px-3 py-2 text-center text-sm text-slate-700 shadow-sm">
                                {{ $pro_color->vName }}
                                <br>
                                Price:{{ $pro_color->price }}---Qnty:{{ $pro_color->qnty }}
                            </label>
                        @endforeach
                    </div>
                @endforeach

                {{-- Pricing / stock summary --}}
                <div class="mt-4 rounded-lg bg-slate-100 px-4 py-3">
                    <h4 class="mb-0 text-base font-semibold text-slate-800">
                        Regular Price: {{ $product->regular_price }}
                    </h4>
                    <h4 class="mt-0 text-base font-semibold text-slate-800">
                        <small class="font-normal text-slate-600">Discount Price: {{ $product->discount_price }}</small>
                    </h4>
                    <h4 class="mt-0 text-base font-semibold text-slate-800">
                        <small class="font-normal text-slate-600">
                            Shipping Charge:
                            @if ($product->shipping_charge)
                                Dummy
                            @else
                                Free
                            @endif
                        </small>
                    </h4>
                    <h4 class="mt-0 text-base font-semibold text-slate-800">
                        <small class="font-normal text-slate-600">Quantity: {{ $product->quantity }}</small>
                    </h4>
                    <h4 class="mt-0 text-base font-semibold text-slate-800">
                        <small class="font-normal text-slate-600">Stock:
                            @if ($product->quantity > 0)
                                Available
                            @else
                                Unavailable
                            @endif
                        </small>
                    </h4>
                    @isset($product->brand->name)
                        <h4 class="mt-0 text-base font-semibold text-slate-800">
                            <small class="font-normal text-slate-600">Brand: {{ $product->brand->name }}</small>
                        </h4>
                    @endisset
                </div>
            </div>
        </div>

        {{-- Tabs: Description / Comments / Rating / Categories / Sub Categories / Tags / Downloads --}}
        <div class="mt-6" x-data="{ tab: 'desc' }">
            {{-- Tab nav --}}
            <div class="flex flex-wrap gap-1 border-b border-slate-200">
                <button type="button"
                        @click="tab='desc'"
                        :class="tab === 'desc' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-desc-tab">Description</button>
                <button type="button"
                        @click="tab='comments'"
                        :class="tab === 'comments' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-comments-tab">Comments</button>
                <button type="button"
                        @click="tab='rating'"
                        :class="tab === 'rating' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-rating-tab">Rating</button>
                <button type="button"
                        @click="tab='categories'"
                        :class="tab === 'categories' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-categories-tab">Categories</button>
                <button type="button"
                        @click="tab='sub_categories'"
                        :class="tab === 'sub_categories' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-sub-categories-tab">Sub Categories</button>
                <button type="button"
                        @click="tab='tags'"
                        :class="tab === 'tags' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2 text-sm font-medium -mb-px"
                        id="product-tags-tab">Tags</button>
                @if ($product->downloads->count() > 0)
                    <button type="button"
                            @click="tab='downloads'"
                            :class="tab === 'downloads' ? 'border-b-2 border-primary text-primary' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2 text-sm font-medium -mb-px"
                            id="product-downloads-tab">Downloadable Files</button>
                @endif
            </div>

            {{-- Tab panels --}}
            <div class="py-4">
                <div x-show="tab === 'desc'" x-cloak id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                    {!! $product->full_description !!}
                </div>
                <div x-show="tab === 'comments'" x-cloak id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"></div>
                <div x-show="tab === 'rating'" x-cloak id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"></div>
                <div x-show="tab === 'categories'" x-cloak id="product-categories" role="tabpanel" aria-labelledby="product-categories-tab">
                    @forelse ($product->categories as $category)
                        @if ($product->categories->last()->id == $category->id)
                            <strong>{{ $category->name }}</strong>
                        @else
                            <strong>{{ $category->name }},</strong>
                        @endif
                    @empty
                        <strong>Not Available</strong>
                    @endforelse
                </div>
                <div x-show="tab === 'sub_categories'" x-cloak id="product-sub-categories" role="tabpanel" aria-labelledby="product-sub-categories-tab">
                    @forelse ($product->sub_categories as $sub_category)
                        @if ($product->sub_categories->last()->id == $sub_category->id)
                            <strong>{{ $sub_category->name }}</strong>
                        @else
                            <strong>{{ $sub_category->name }},</strong>
                        @endif
                    @empty
                        <strong>Not Available</strong>
                    @endforelse
                </div>
                <div x-show="tab === 'tags'" x-cloak id="product-tags" role="tabpanel" aria-labelledby="product-tags-tab">
                    @forelse ($product->tags as $key => $tag)
                        @if ($product->tags->last()->id == $tag->id)
                            <strong>{{ $tag->name }}</strong>
                        @else
                            <strong>{{ $tag->name }},</strong>
                        @endif
                    @empty
                        <strong>Not Available</strong>
                    @endforelse
                </div>
                @if ($product->downloads->count() > 0)
                    <div x-show="tab === 'downloads'" x-cloak id="product-downloads" role="tabpanel" aria-labelledby="product-downloads-tab">
                        @foreach ($product->downloads as $key => $download)
                            <div class="mb-3">
                                <p><strong>File Name: </strong><a href="">{{ $download->name }}</a></p>
                                @if ($download->url != null)
                                    <p><strong>File URL: </strong><a href="">{{ $download->url }}</a></p>
                                @else
                                    <p><strong>File URL: </strong><a href="">{{ asset('uploads/product/download/' . $download->url) }}</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </x-ui.card>

@endsection

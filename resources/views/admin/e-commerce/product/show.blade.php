@extends('layouts.admin.app')

@section('title', 'Product Information')

@section('content')
    <style type="text/css">
        @import url('https://fonts.cdnfonts.com/css/siyam-rupali');
        @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap');

        .banner-bootom-w3-agileits {
            font-family: 'Siyam Rupali', sans-serif !important;
            font-family: monospace;
        }

        #specification th {
            width: 160px;
            background: #f1f1f1;
        }

        label.btn.rounded-circle.active {
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        .p_title {
            word-spacing: 3px;
            font-weight: 300;
            margin-bottom: 14px;
            color: #333;
        }

        @media(max-width:767px) {
            .new_r {
                display: none;
            }
        }

        .new_r {
            background: #f3f3f3;
            margin-top: -20px;
            padding-top: 18px;
            position: relative;
            right: -10px;
        }

        .s_d,
        .rating1 {
            margin-top: 10px;
        }

        .s_d * {
            margin: 0;
        }

        .media img {
            width: 50px;
            height: 50px;
        }

        .reply a {
            text-decoration: none;
        }

        .heading {
            font-size: 25px;
            margin-right: 25px;
        }

        .checked {
            color: orange;
        }

        .side {
            float: left;
            width: 15%;
            margin-top: 10px;
        }

        .item_price,
        del {
            font-family: monospace;
            font-weight: 600;
        }

        .middle {
            float: left;
            width: 70%;
            margin-top: 10px;
        }

        .right {
            text-align: right;
        }

        .bar-container {
            width: 100%;
            background-color: #f1f1f1;
            text-align: center;
            color: white;
        }

        .dropdown .dropdown-menu {
            box-shadow: 0px 0px 5px gainsboro;
            padding: 10px;
        }

        .bar-5 {
            width: 60%;
            height: 18px;
            background-color: #04AA6D;
        }

        .bar-4 {
            width: 30%;
            height: 18px;
            background-color: #2196F3;
        }

        .bar-3 {
            width: 10%;
            height: 18px;
            background-color: #00bcd4;
        }

        .bar-2 {
            width: 4%;
            height: 18px;
            background-color: #ff9800;
        }

        .bar-1 {
            width: 15%;
            height: 18px;
            background-color: #f44336;
        }

        @media (max-width: 400px) {
            .side,
            .middle {
                width: 100%;
            }

            .right {
                display: none;
            }
        }

        .rounded-10 {
            border-radius: 10px;
        }

        #comment-reply i {
            font-size: 14px;
        }

        .single-right-left p {
            color: #54595F;
            font-size: 18px;
            font-weight: 300;
            line-height: 1.3em;
            margin-top: 10px;
            margin-bottom: 0;
        }

        .crv img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin: 5px;
            border: 2px solid black;
        }

        /* Product tab nav active/hover states */
        .product-tab-nav a.active {
            border-bottom: 2px solid #e66000;
            color: #e66000;
        }

        .product-tab-nav a:hover {
            color: #e66000;
        }
    </style>

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold text-slate-800">Product Information</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Show Product</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">

        <x-ui.card>
            <x-slot:header>
                <div class="flex flex-wrap items-center justify-between">
                    <h3 class="text-base font-semibold text-slate-800">Product Details</h3>
                    <div class="flex gap-2">
                        <x-ui.button variant="info" :href="routeHelper('product/' . $product->id . '/edit')">
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

            {{-- Product image + details row --}}
            <div class="flex flex-wrap -mx-2">

                {{-- Left: image + thumbnails --}}
                <div class="w-full md:w-1/2 px-2">
                    <h3 class="block md:hidden my-3 text-lg font-semibold">{{ $product->title }}</h3>
                    <div class="mb-3">
                        <img src="{{ asset('uploads/product/' . $product->image) }}"
                            class="product-image w-full object-contain max-h-80"
                            alt="Product Image">
                    </div>
                    <div class="flex flex-wrap gap-2 product-image-thumbs">
                        <div class="product-image-thumb active border-2 border-primary rounded overflow-hidden w-16 h-16">
                            <img src="{{ asset('uploads/product/' . $product->image) }}"
                                alt="Product Image" class="w-full h-full object-cover">
                        </div>
                        @foreach ($product->images as $image)
                            <div class="product-image-thumb active border-2 border-primary rounded overflow-hidden w-16 h-16">
                                <img src="{{ asset('uploads/product/' . $image->name) }}"
                                    alt="Product Image" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right: product info --}}
                <div class="w-full md:w-1/2 px-2">
                    <h3 class="hidden md:block my-3 text-xl font-semibold text-slate-800">{{ $product->title }}</h3>
                    <p class="text-slate-600">{{ $product->short_description }}</p>

                    <hr class="my-4 border-slate-200">

                    <h4 class="text-base font-semibold mb-2">Available Colors</h4>
                    <div class="btn-group btn-group-toggle flex flex-wrap gap-2" data-toggle="buttons">
                        @foreach ($colors_product as $color)
                            <label class="btn btn-default text-center active inline-block border border-slate-300 rounded px-3 py-2 text-sm cursor-pointer">
                                {{ $color->name }}
                                <br>
                                <i class="fas fa-circle fa-2x" style="color: {{ $color->code }}"></i>
                                <br>
                                Price:{{ $color->price }}---Qnty:{{ $color->qnty }}
                            </label>
                        @endforeach
                    </div>

                    @foreach ($attributes as $attribute)
                        <h4 class="mt-4 mb-2 text-base font-semibold">Available {{ $attribute->name }}</h4>
                        <div class="btn-group btn-group-toggle flex flex-wrap gap-2" data-toggle="buttons">
                            <?php
                            $attribute_prouct = DB::table('attribute_product')->select('*')->join('attribute_values', 'attribute_values.id', '=', 'attribute_product.attribute_value_id')->addselect('attribute_values.name as vName')->addselect('attribute_product.id as vid')->join('attributes', 'attributes.id', '=', 'attribute_values.attributes_id')->where('attribute_product.product_id', $product->id)->where('attributes.id', $attribute->id)->get();
                            ?>
                            @foreach ($attribute_prouct as $pro_color)
                                <label class="btn btn-default text-center active inline-block border border-slate-300 rounded px-3 py-2 text-sm cursor-pointer">
                                    {{ $pro_color->vName }}
                                    <br>
                                    Price:{{ $pro_color->price }}---Qnty:{{ $pro_color->qnty }}
                                </label>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="bg-slate-100 rounded py-3 px-4 mt-4">
                        <h4 class="mb-0 text-base font-semibold">
                            Regular Price: {{ $product->regular_price }}
                        </h4>
                        <h4 class="mt-1 text-base font-medium">
                            <small>Discount Price: {{ $product->discount_price }}</small>
                        </h4>
                        <h4 class="mt-1 text-base font-medium">
                            <small>
                                Shipping Charge:
                                @if ($product->shipping_charge)
                                    Dummy
                                @else
                                    Free
                                @endif
                            </small>
                        </h4>
                        <h4 class="mt-1 text-base font-medium"><small>Quantity: {{ $product->quantity }}</small></h4>
                        <h4 class="mt-1 text-base font-medium">
                            <small>Stock:
                                @if ($product->quantity > 0)
                                    Available
                                @else
                                    Unavailable
                                @endif
                            </small>
                        </h4>
                        @isset($product->brand->name)
                            <h4 class="mt-1 text-base font-medium"><small>Brand: {{ $product->brand->name }}</small></h4>
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Tabs section --}}
            <div class="mt-6">
                <nav class="w-full">
                    <div class="product-tab-nav flex flex-wrap border-b border-slate-200" id="product-tab" role="tablist">
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent active"
                            id="product-desc-tab" data-toggle="tab" href="#product-desc"
                            role="tab" aria-controls="product-desc" aria-selected="true">Description</a>
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                            id="product-comments-tab" data-toggle="tab"
                            href="#product-comments" role="tab" aria-controls="product-comments"
                            aria-selected="false">Comments</a>
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                            id="product-rating-tab" data-toggle="tab" href="#product-rating"
                            role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                            id="product-categories-tab" data-toggle="tab"
                            href="#product-categories" role="tab" aria-controls="product-categories"
                            aria-selected="false">Categories</a>
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                            id="product-sub-categories-tab" data-toggle="tab"
                            href="#product-sub-categories" role="tab" aria-controls="product-sub-categories"
                            aria-selected="false">Sub Categories</a>
                        <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                            id="product-tags-tab" data-toggle="tab" href="#product-tags"
                            role="tab" aria-controls="product-tags" aria-selected="false">Tags</a>
                        @if ($product->downloads->count() > 0)
                            <a class="nav-item nav-link px-4 py-2 text-sm font-medium text-slate-600 -mb-px border-b-2 border-transparent"
                                id="product-downloads-tab" data-toggle="tab"
                                href="#product-downloads" role="tab" aria-controls="product-downloads"
                                aria-selected="false">Downloadable Files</a>
                        @endif
                    </div>
                </nav>

                <div class="tab-content p-3 w-full" id="nav-tabContent">

                    {{-- Description --}}
                    <div class="tab-pane fade show active" id="product-desc" role="tabpanel"
                        aria-labelledby="product-desc-tab">
                        {!! $product->full_description !!}
                    </div>

                    {{-- Comments --}}
                    <div class="tab-pane fade" id="product-comments" role="tabpanel"
                        aria-labelledby="product-comments-tab">
                        <div class="p-4">
                            <div class="my-2">
                                <div class="{{ $product->comments->count() > 0 ? 'rounded-lg border border-slate-200 bg-white shadow-sm' : '' }}">
                                    <div>
                                        <div class="w-full">
                                            <div class="w-full">
                                                @forelse ($product->comments->where('parent_id',null) as $comment)
                                                    <div class="media flex gap-3 mb-4">
                                                        <img class="mr-3 rounded-10 shrink-0" alt="Avatar"
                                                            src="{{ $comment->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $comment->user->avatar }}" />
                                                        <div class="media-body flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center">
                                                                    <h5 class="font-semibold text-slate-800">{{ $comment->user->name }}</h5>
                                                                </div>
                                                                <div>
                                                                    <x-ui.button variant="danger" size="sm"
                                                                        :href="route('admin.comment', $comment->id)">Delete</x-ui.button>
                                                                </div>
                                                            </div>
                                                            <p style="margin-top:-7px"> {{ $comment->body }}</p>
                                                            <p style="font-size: 11px;color: #3e3939;">
                                                                {{ $comment->created_at->diffForHumans() }}</p>
                                                            @forelse ($comment->replies as $reply)
                                                                <div class="media flex gap-3 mt-4">
                                                                    <a class="pr-3 shrink-0" href="#">
                                                                        <img class="rounded-10" alt="Avatar"
                                                                            src="{{ $reply->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $reply->user->avatar }}" />
                                                                    </a>
                                                                    <div class="media-body flex-1">
                                                                        <div class="flex items-center justify-between">
                                                                            <div class="flex items-center">
                                                                                <h5 class="font-semibold text-slate-800">{{ $reply->user->name }}</h5>
                                                                            </div>
                                                                            <div>
                                                                                <x-ui.button variant="danger" size="sm"
                                                                                    :href="route('admin.comment', $reply->id)">Delete</x-ui.button>
                                                                            </div>
                                                                        </div>
                                                                        <p style="margin-top:-7px">
                                                                            {{ $reply->body }}</p>
                                                                        <p style="font-size: 11px;color: #3e3939;">
                                                                            {{ $reply->created_at->diffForHumans() }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                            @endforelse

                                                            <div class="reply-box"></div>

                                                        </div>
                                                    </div>
                                                @empty
                                                    <h3 class="text-center text-danger">Comments not available</h3>
                                                @endforelse

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="tab-pane fade" id="product-rating" role="tabpanel"
                        aria-labelledby="product-rating-tab">
                        <div class="{{ $product->reviews->count() > 0 ? 'rounded-lg border border-slate-200 bg-white shadow-sm' : '' }} mb-5">
                            @forelse ($product->reviews as $review)
                                <div class="flex flex-wrap gap-3 mb-4">
                                    <div class="review-head w-12 shrink-0">
                                        @if ($review->user->avatar)
                                            <img src="{{ $review->user->avatar == 'default.png' ? '/default/default.png' : '/uploads/admin/' . $review->user->avatar }}"
                                                alt="" class="w-10 h-10 rounded-full object-cover">
                                        @endif
                                    </div>
                                    <div class="side-2 flex-1">
                                        <div class="flex items-center gap-2">
                                            <b>{{ $review->user->name }}</b>
                                            <x-ui.button variant="danger" size="sm"
                                                :href="route('admin.rating', $review->id)">Delete</x-ui.button>
                                            <x-ui.button variant="warning" size="sm"
                                                :href="route('admin.rating.edit', $review->id)">Edit</x-ui.button>
                                        </div>

                                        <div class="rating1">
                                            @if ($review->rating == 1)
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            @elseif ($review->rating == 2)
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            @elseif ($review->rating == 3)
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            @elseif ($review->rating == 4)
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            @elseif ($review->rating == 5)
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                            @endif
                                            <span style="color: #333;">{{ $review->rating }} rating</span>
                                        </div>

                                        <p style="margin-bottom: 0;">{{ $review->body }}</p>
                                        <div class="flex flex-wrap crv">
                                            @if ($review->file)
                                                <a href="{{ asset('/') }}uploads/review/{{ $review->file }}">
                                                    <img width="100px"
                                                        src="{{ asset('/') }}uploads/review/{{ $review->file }}">
                                                </a>
                                            @endif
                                            @if ($review->file2)
                                                <a href="{{ asset('/') }}uploads/review/{{ $review->file2 }}">
                                                    <img width="100px"
                                                        src="{{ asset('/') }}uploads/review/{{ $review->file2 }}">
                                                </a>
                                            @endif
                                            @if ($review->file3)
                                                <a href="{{ asset('/') }}uploads/review/{{ $review->file3 }}">
                                                    <img width="100px"
                                                        src="{{ asset('/') }}uploads/review/{{ $review->file3 }}">
                                                </a>
                                            @endif
                                            @if ($review->file4)
                                                <a href="{{ asset('/') }}uploads/review/{{ $review->file4 }}">
                                                    <img width="100px"
                                                        src="{{ asset('/') }}uploads/review/{{ $review->file4 }}">
                                                </a>
                                            @endif
                                            @if ($review->file5)
                                                <a href="{{ asset('/') }}uploads/review/{{ $review->file5 }}">
                                                    <img width="100px"
                                                        src="{{ asset('/') }}uploads/review/{{ $review->file5 }}">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="w-full">
                                    <h3 class="text-center text-danger">Reviews not available</h3>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="tab-pane fade" id="product-categories" role="tabpanel"
                        aria-labelledby="product-categories-tab">
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

                    {{-- Sub Categories --}}
                    <div class="tab-pane fade" id="product-sub-categories" role="tabpanel"
                        aria-labelledby="product-sub-categories-tab">
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

                    {{-- Tags --}}
                    <div class="tab-pane fade" id="product-tags" role="tabpanel" aria-labelledby="product-tags-tab">
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

                    {{-- Downloads --}}
                    <div class="tab-pane fade" id="product-downloads" role="tabpanel"
                        aria-labelledby="product-downloads-tab">
                        @foreach ($product->downloads as $key => $download)
                            <div class="mb-3">
                                <p><strong>File Name: </strong><a href="">{{ $download->name }}</a></p>
                                @if ($download->url != null)
                                    <p><strong>File URL: </strong><a href="">{{ $download->url }}</a></p>
                                @else
                                    <p><strong>File URL: </strong><a
                                            href="">{{ asset('uploads/product/download/' . $download->url) }}</a>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </x-ui.card>

    </section>

@endsection

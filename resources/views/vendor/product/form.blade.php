@extends('layouts.vendor.app')

@section('title')
    @isset($product)
        Edit Product
    @else
        Add Product
    @endisset
@endsection

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/assets/plugins/dropzone/min/dropzone.min.css">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link type="text/css" rel="stylesheet" href="/assets/plugins/file-uploader/image-uploader.min.css">
    {{-- Kept: pseudo-element / plugin-DOM selectors cannot be expressed as Tailwind utilities --}}
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .custom-file::-webkit-file-upload-button {
            visibility: hidden;
        }

        .custom-file::before {
            content: 'Choose File';
            display: inline-block;
            background: linear-gradient(top, #f9f9f9, #e3e3e3);
            border: 1px solid #ced4da;
            border-radius: 3px;
            padding: 8px 8px;
            outline: none;
            white-space: nowrap;
            -webkit-user-select: none;
            cursor: pointer;
            text-shadow: 1px 1px #fff;
            font-weight: 700;
            font-size: 10pt;
            width: 100%;
            text-align: center;
        }
    </style>
@endpush

@section('content')

    @if (auth()->user()->is_approved == 0)
        <p class="text-2xl p-5 text-center mx-auto text-red-600">Your Account
            is Under Review.You Cant Upload Product At This Time</p>
    @else
        {{-- Page header --}}
        <section class="mb-4">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <h1 class="text-2xl font-semibold text-slate-800">
                    @isset($product)
                        Edit Product
                    @else
                        Add Product
                    @endisset
                </h1>
                <nav class="text-sm text-slate-500">
                    <a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a>
                    <span class="mx-1">/</span>
                    <span>
                        @isset($product)
                            Edit Product
                        @else
                            Add Product
                        @endisset
                    </span>
                </nav>
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
                <div class="border-b border-slate-200 px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h3 class="font-medium text-slate-900">
                            @isset($product)
                                Edit Product
                            @else
                                Add New Product
                            @endisset
                        </h3>
                        <div class="flex items-center gap-2">
                            @isset($product)
                                <x-ui.button variant="secondary" :href="routeHelper('product/' . $product->id)">
                                    <i class="fas fa-eye"></i>
                                    Show
                                </x-ui.button>
                            @endisset
                            <x-ui.button variant="danger" :href="routeHelper('product')">
                                <i class="fas fa-long-arrow-alt-left"></i>
                                Back to List
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                <form action="{{ isset($product) ? routeHelper('product/' . $product->id) : routeHelper('product') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($product)
                        @method('PUT')
                    @endisset
                    <div class="p-4">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title <span class="text-danger">(*)</span>:</label>
                            <input type="text" name="title" id="title" placeholder="Write product title"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('title') border-danger @else border-slate-300 @enderror"
                                value="{{ $product->title ?? old('title') }}" required>
                            @error('title')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="sku" class="block text-sm font-medium text-slate-700 mb-1">Product Code (SKU):</label>
                            <input type="text" name="sku" id="sku" placeholder="Product Code/SKU"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                value="{{ $product->sku ?? old('sku') }}">
                        </div>
                        <div class="mb-4">
                            <label for="short_description" class="block text-sm font-medium text-slate-700 mb-1">Short Description <span class="text-danger">(*)</span>:</label>
                            <textarea name="short_description" id="short_description" rows="3" placeholder="Write product short description"
                                class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('short_description') border-danger @else border-slate-300 @enderror" required>{{ $product->short_description ?? old('short_description') }}</textarea>
                            @error('short_description')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="full_description" class="block text-sm font-medium text-slate-700 mb-1">Full Description <span class="text-danger">(*)</span>:</label>
                            <textarea name="full_description" id="full_description" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $product->full_description ?? old('full_description') }}</textarea>
                            @error('full_description')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-wrap -mx-2">
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="buying_price" class="block text-sm font-medium text-slate-700 mb-1">Buying Price:</label>
                                <input type="number" name="buying_price" id="buying_price"
                                    placeholder="Enter product buying price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('buying_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->buying_price ?? old('buying_price') }}">
                                @error('buying_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="regular_price" class="block text-sm font-medium text-slate-700 mb-1">Regular Price <span class="text-danger">(*)</span>:</label>
                                <input type="number" name="regular_price" id="regular_price"
                                    placeholder="Enter product regular price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('regular_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->regular_price ?? old('regular_price') }}" required>
                                @error('regular_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="whole_price" class="block text-sm font-medium text-slate-700 mb-1">Whole Sell Price:</label>
                                <input type="number" name="whole_price" id="whole_price"
                                    placeholder="Enter product whole sell price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('whole_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->whole_price ?? old('whole_price') }}">
                                @error('whole_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="prdct_extra_msg" class="block text-sm font-medium text-slate-700 mb-1">Product Extra Message:</label>
                                <input type="text" name="prdct_extra_msg" id="prdct_extra_msg"
                                    placeholder="Express Delivery in Dhaka"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('prdct_extra_msg') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->prdct_extra_msg ?? '' }}">
                                @error('prdct_extra_msg')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <x-ui.select name="dis_type" id="dis_type" label="Discount Type:">
                                    <option value="0"
                                        @isset($product) {{ $product->dis_type == '0' ? 'selected' : '' }} @endisset>
                                        None</option>
                                    <option value="1"
                                        @isset($product) {{ $product->dis_type == '1' ? 'selected' : '' }} @endisset>
                                        Flat</option>
                                    <option value="2"
                                        @isset($product) {{ $product->dis_type == '2' ? 'selected' : '' }} @endisset>
                                        Parcent %</option>
                                </x-ui.select>
                                @error('dis_type')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @isset($product)
                                @if ($product->dis_type == '2')
                                    @php($discount_price = (($product->regular_price - $product->discount_price) / $product->regular_price) * 100)
                                @else
                                    @php($discount_price = $product->regular_price - $product->discount_price)
                                @endif
                            @endisset
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="discount_price" class="block text-sm font-medium text-slate-700 mb-1">Discount:</label>
                                <input type="number" name="discount_price" id="discount_price"
                                    placeholder="Enter product discount price"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('discount_price') border-danger @else border-slate-300 @enderror"
                                    value="{{ $discount_price ?? old('discount_price') }}">
                                @error('discount_price')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">Quantity <span class="text-danger">(*)</span>:</label>
                                <input type="number" name="quantity" id="quantity"
                                    placeholder="Enter product quantity"
                                    class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('quantity') border-danger @else border-slate-300 @enderror"
                                    value="{{ $product->quantity ?? old('quantity') }}" required>
                                @error('quantity')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <x-ui.select name="brand" id="brand" label="Select Brand:" data-placeholder="Select Brand" class="select2">
                                    <option value="0">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            @isset($product) {{ $brand->id == $product->brand_id ? 'selected' : '' }} @endisset>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </x-ui.select>
                                @error('brand')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Select Category <span class="text-danger">(*)</span>:</label>
                                <select name="categories[]" id="category" multiple data-placeholder="Select Category"
                                    class="category select2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('categories') border-danger @enderror"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @isset($product) @foreach ($product->categories as $pro_category) {{ $category->id == $pro_category->id ? 'selected' : '' }} @endforeach @endisset>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="sub_category" class="block text-sm font-medium text-slate-700 mb-1">Select Sub Category:</label>
                                <select name="sub_categories[]" id="sub_category" data-placeholder="Select Sub Category"
                                    class="sub_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('sub_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->sub_categories as $sub_category)
                                            <option value="{{ $sub_category->id }}" selected>{{ $sub_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('sub_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="mini_category" class="block text-sm font-medium text-slate-700 mb-1">Select Mini Category:</label>
                                <select name="mini_categories[]" id="mini_category"
                                    data-placeholder="Select Mini Category"
                                    class="mini_category block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('mini_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->mini_categories as $mini_category)
                                            <option value="{{ $mini_category->id }}" selected>{{ $mini_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('mini_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="extra_category" class="block text-sm font-medium text-slate-700 mb-1">Select Extra Category:</label>
                                <select name="extra_categories[]" id="extra_category"
                                    data-placeholder="Select Mini Category"
                                    class="extra_categories block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary {{ isset($product) ? 'select2' : '' }} @error('mini_categories') border-danger @enderror"
                                    {{ isset($product) ? 'multiple' : '' }}>
                                    @isset($product)
                                        @foreach ($product->extra_categories as $extra_category)
                                            <option value="{{ $extra_category->id }}" selected>{{ $extra_category->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                                @error('extra_categories')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="tag" class="block text-sm font-medium text-slate-700 mb-1">Select Tag:</label>
                                <select name="tags[]" id="tag" multiple data-placeholder="Select Tag"
                                    class="select2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('tags') border-danger @enderror">
                                    <option value="">Select Tag</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @isset($product) @foreach ($product->tags as $pro_tag) {{ $tag->id == $pro_tag->id ? 'selected' : '' }} @endforeach @endisset>
                                            {{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                @error('tags')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type='hidden' name="shipping_charge" value="1">
                            <div class="w-full px-2 mb-4">
                                <div class="bg-[#eeeeee] p-[10px] rounded-[5px]">
                                    <div class="mb-1 border border-[gainsboro]" x-data="{ open: false }">
                                        <label class="block" for="color">
                                            <button class="flex items-center w-full text-left px-2 py-1" type="button"
                                                @click="open = !open">
                                                Select Color:<i class="fas fa-arrow-down ml-auto"></i>
                                            </button>
                                        </label>
                                        <div id="collapseExampleColor" x-show="open" x-cloak>
                                            <div class="flex">
                                                <select id="select_color" data-placeholder="Select Color"
                                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('colors') border-danger @enderror">
                                                    <option value="">Select Color</option>
                                                    @foreach ($colors as $color)
                                                        <option style="color:white;background: {{ $color->code }}"
                                                            value="{{ $color->slug . ',' . $color->id }}">
                                                            {{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('colors')
                                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div id="increment_color">
                                                @isset($product)
                                                    @foreach ($colors_product as $pro_color)
                                                        <div class="flex gap-1 mt-2">
                                                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="hidden" readonly=""
                                                                name="colors[]" value="{{ $pro_color->id }}">
                                                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="text" readonly=""
                                                                value="{{ $pro_color->name }}">
                                                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="number"
                                                                placeholder="extra price" name="color_prices[]"
                                                                value="{{ $pro_color->price }}">
                                                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="number"
                                                                placeholder="extra quantity" name="color_quantits[]"
                                                                value="{{ $pro_color->qnty }}">
                                                            <div id="remove" class="cursor-context-menu">
                                                                <a href="{{ route('admin.color.delete.n2', ['cc' => $pro_color->id, 'pp' => $product->id]) }}"
                                                                    class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 bg-slate-100 text-sm">Remove</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <div id="sho_attributes" class="flex flex-wrap -mx-2">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Product Image <span class="text-danger">(*)</span>: <a
                                        target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="text-primary underline">How to Optimize
                                        Image</a></label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm dropify @error('image') border-danger @enderror"
                                    data-default-file="@isset($product) /uploads/product/{{ $product->image }}@enderror">
                            @error('image')
                                <p class="text-sm text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @isset($product)
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Product Gallery Image <span class="text-danger">(*)</span>: <a target="_blank" href="https://youtu.be/JsZc-I_Wygk" class="text-primary underline">How to Optimize Image</a></label>
                                <div class="flex gap-1" id="increment">
                                    <input type="file" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" accept="image/*" id="images" name="images[]"
                                        ___inline_directive______________________________________24___>
                                    <select name="imagesc[]" id="imagesc" class="block rounded-md border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Select Color</option>
                                        @foreach ($colors as $color)
                                            <option style="color:white;background: {{ $color->code }}"
                                                value="{{ $color->slug }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="add" class="cursor-context-menu">
                                        <span class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 bg-slate-100 text-sm">Add More</span>
                                    </div>
                                </div>
                                @error('images')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                                {{-- .d style folded into Tailwind utilities on the divs below --}}
                                @isset($product)
                                    @foreach ($product->images as $image)
                                        <div class="flex items-center p-[10px] my-[10px] rounded-[5px]" ___inline_directive____________________________________________________________________________________________________________________________________________25___each>
                                            <img src="{{ asset('uploads/product/' . $image->name) }}"
                                                class="w-[100px] h-[70px] object-cover">
                                            <div class="flex-1 text-right">
                                                <x-ui.button variant="danger" :href="route('admin.idelte', $image->id)">Delete</x-ui.button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
@else
                            <div class="w-full md:w-1/2 px-2 mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Product Gallery Image <span class="text-danger">(*)</span>: <a target="_blank"
                                        href="https://youtu.be/JsZc-I_Wygk" class="text-primary underline">How to Optimize Image</a></label>
                                <div class="flex gap-1" id="increment">
                                    <input type="file" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" accept="image/*" id="images"
                                        name="images[]" required>

                                    <div id="add" class="cursor-context-menu">
                                        <span class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 bg-slate-100 text-sm">Add More</span>
                                    </div>
                                </div>
                                @error('images')
                                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endisset
                    </div>


                    <div class="mb-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="download_able" id="download_able"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                                @isset($product){{ $product->download_able ? 'checked' : '' }} @endisset>
                            <span class="text-sm font-medium text-slate-700">Download able</span>
                        </label>
                        @error('download_able')
                            <p class="text-sm text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @isset($product)
                        @if ($product->downloads->count() < 1)
                            <x-ui.modal name="modal-default" title="Add Product Downloadable file" size="xl">
                                <div class="space-y-4">
                                    <div class="flex flex-wrap gap-4">
                                        <label class="w-full sm:w-1/6 text-sm font-medium text-slate-700 pt-2">Downloadable Files</label>
                                        <div class="flex-1">
                                            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                                                <div class="border-b border-slate-200 px-4 py-3">
                                                    <div class="flex gap-4">
                                                        <div class="w-1/2"><strong>Name:</strong></div>
                                                        <div class="w-1/2"><strong>File URL:</strong></div>
                                                    </div>
                                                </div>
                                                <div class="px-1 py-2">
                                                    <div id="increment-file"></div>
                                                </div>
                                                <div class="border-t border-slate-200 px-4 py-3">
                                                    <x-ui.button type="button" variant="secondary" id="add-file">Add File</x-ui.button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-4 items-center">
                                        <label for="download_limit" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Limit</label>
                                        <div class="w-full sm:w-1/3">
                                            <input type="number" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                                id="download_limit" name="download_limit"
                                                value="{{ $product->download_limit ?? old('download_limit') }}">
                                        </div>
                                        <div class="flex-1 text-sm text-slate-500">Leave blank for unlimited re-downloads</div>
                                    </div>
                                    <div class="flex flex-wrap gap-4 items-center">
                                        <label for="download_expire" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Expire</label>
                                        <div class="w-full sm:w-1/3">
                                            <input type="date" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                                id="download_expire" name="download_expire"
                                                value="{{ $product->download_expire ?? old('download_expire') }}">
                                        </div>
                                        <div class="flex-1 text-sm text-slate-500">Enter the number of days before a downlink link expires, or leave blank</div>
                                    </div>
                                </div>
                                <x-slot:footer>
                                    <x-ui.button type="button" variant="secondary" x-on:click="open = false">Close</x-ui.button>
                                    {{-- <button type="submit" class="btn btn-primary">Add</button> --}}
                                </x-slot:footer>
                            </x-ui.modal>
                        @endif
                    @else
                        <x-ui.modal name="modal-default" title="Add Product Downloadable file" size="xl">
                            <div class="space-y-4">
                                <div class="flex flex-wrap gap-4">
                                    <label class="w-full sm:w-1/6 text-sm font-medium text-slate-700 pt-2">Downloadable Files</label>
                                    <div class="flex-1">
                                        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                                            <div class="border-b border-slate-200 px-4 py-3">
                                                <div class="flex gap-4">
                                                    <div class="w-1/2"><strong>Name:</strong></div>
                                                    <div class="w-1/2"><strong>File URL:</strong></div>
                                                </div>
                                            </div>
                                            <div class="px-1 py-2">
                                                <div id="increment-file"></div>
                                            </div>
                                            <div class="border-t border-slate-200 px-4 py-3">
                                                <x-ui.button type="button" variant="secondary" id="add-file">Add File</x-ui.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-4 items-center">
                                    <label for="download_limit" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Limit</label>
                                    <div class="w-full sm:w-1/3">
                                        <input type="number" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                            id="download_limit" name="download_limit"
                                            value="{{ $product->download_limit ?? old('download_limit') }}">
                                    </div>
                                    <div class="flex-1 text-sm text-slate-500">Leave blank for unlimited re-downloads</div>
                                </div>
                                <div class="flex flex-wrap gap-4 items-center">
                                    <label for="download_expire" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Expire</label>
                                    <div class="w-full sm:w-1/3">
                                        <input type="date" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                            id="download_expire" name="download_expire"
                                            value="{{ $product->download_expire ?? old('download_expire') }}">
                                    </div>
                                    <div class="flex-1 text-sm text-slate-500">Enter the number of days before a downlink link expires, or leave blank</div>
                                </div>
                            </div>
                            <x-slot:footer>
                                <x-ui.button type="button" variant="secondary" x-on:click="open = false">Close</x-ui.button>
                                {{-- <button type="submit" class="btn btn-primary">Add</button> --}}
                            </x-slot:footer>
                        </x-ui.modal>
                    @endisset


                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary" class="mt-1">
                            @isset($product)
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

        {{-- @isset($product)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Update Product Gallery Images</h3>
        </div>
        <div class="card-body">
            <form action="{{routeHelper('update/product/image')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group col-md-6">
                    <label>Product Gallery Image <span class="text-danger">(*)</span>:</label>
                    <div class="input-group" id="increment">
                        <input type="file" class="form-control" accept="image/*" id="images" name="images[]"
                            @isset($product) @else required @endisset>
                        <select name="imagesc[]" id="imagesc">
                            <option value="">Select Color</option>
                            @foreach ($colors as $color)
                                <option style="color:white;background: {{ $color->code }}"
                                    value="{{ $color->slug }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append" id="add" style="cursor:context-menu">
                            <span class="input-group-text">Add More</span>
                        </div>
                    </div>
                    @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <style type="text/css">
                        .d {
                            display: flex;
                            align-items: center;
                            padding: 10px;
                            margin: 10px 0px;
                            border-radius: 5px;
                        }
                    </style>
                    @isset($product)
                        @foreach ($product->images as $image)
                            <div class="d" @foreach ($colors as $color) @if ($color->slug == $image->color_attri)
                                style="background: {{ $color->code }}" @endif @endforeach>
                                <img src="{{ asset('uploads/product/'.$image->name) }}"
                                    style="width: 100px;height: 70px;object-fit: cover;">
                                <div style="flex: 1;text-align: right;">
                                    <a class="btn btn-danger"
                                        href="{{ route('admin.idelte',$image->id) }}">Delete</a>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-arrow-circle-up"></i>
                    Update
                </button>
            </form>
        </div>
    </div>
    @endisset --}}

        @if (isset($product->downloads) && $product->downloads->count() > 0)
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm mt-4">
                <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">Update Product Download File</div>
                <form action="{{ routeHelper('update/product/download') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="p-4 space-y-4">
                        <div class="flex flex-wrap gap-4">
                            <label for="inputEmail3" class="w-full sm:w-1/6 text-sm font-medium text-slate-700 pt-2">Downloadable Files</label>
                            <div class="flex-1">
                                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                                    <div class="border-b border-slate-200 px-4 py-3">
                                        <div class="flex gap-4">
                                            <div class="w-1/2"><strong>Name:</strong></div>
                                            <div class="w-1/2"><strong>File URL:</strong></div>
                                        </div>
                                    </div>
                                    <div class="px-1 py-2">
                                        <div id="increment-file">
                                            @isset($product->downloads)
                                                @foreach ($product->downloads as $download)
                                                    <div class="flex flex-wrap gap-2 mt-2">
                                                        <div class="w-full md:w-5/12">
                                                            <input type="text" name="file_name[]"
                                                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="Enter file name"
                                                                value="{{ $download->name }}">
                                                        </div>
                                                        <div class="w-full md:w-5/12">
                                                            <input type="text" name="file_url[]"
                                                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="Enter file url"
                                                                value="{{ $download->url }}">
                                                        </div>
                                                        <div class="w-full md:w-auto">
                                                            <input type="file" name="files[]" class="custom-file">
                                                        </div>
                                                        <div class="w-full md:w-auto">
                                                            <input type="hidden" name="ids[]"
                                                                value="{{ $download->id }}">
                                                            <a href="#" id="remove-file"
                                                                data-id="{{ $download->id }}"
                                                                class="inline-flex items-center gap-1 rounded-md bg-danger px-3 py-2 text-sm font-medium text-white hover:opacity-90"><i
                                                                    class="fa fa-trash-alt"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>
                                    <div class="border-t border-slate-200 px-4 py-3">
                                        <x-ui.button type="button" variant="secondary" id="add-file">Add File</x-ui.button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4 items-center">
                            <label for="download_limit" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Limit</label>
                            <div class="w-full sm:w-1/3">
                                <input type="number" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" id="download_limit" name="download_limit"
                                    value="{{ $product->download_limit ?? old('download_limit') }}">
                            </div>
                            <div class="flex-1 text-sm text-slate-500">Leave blank for unlimited re-downloads</div>
                        </div>
                        <div class="flex flex-wrap gap-4 items-center">
                            <label for="download_expire" class="w-full sm:w-1/6 text-sm font-medium text-slate-700">Download Expire</label>
                            <div class="w-full sm:w-1/3">
                                <input type="date" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" id="download_expire"
                                    name="download_expire"
                                    value="{{ $product->download_expire ?? old('download_expire') }}">
                            </div>
                            <div class="flex-1 text-sm text-slate-500">Enter the number of days before a downlink link expires, or leave blank</div>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">Update</x-ui.button>
                    </div>
                </form>
            </div>
        @endif


    </section>
@endif
@endsection

@push('js')
<!-- Select2 -->
<script src="/assets/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
<script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>

<script type="text/javascript" src="/assets/plugins/file-uploader/image-uploader.min.js"></script>
@isset($product)
    @if ($product->downloads->count() < 1)
        <script>
            $(document).on('click', '#download_able', function(e) {
                if (this.checked) {
                    window.dispatchEvent(new CustomEvent('open-modal-modal-default'));
                } else {
                    window.dispatchEvent(new CustomEvent('close-modal-modal-default'));
                }
            })
        </script>
    @endif
@else
    <script>
        $(document).on('click', '#download_able', function(e) {
            if (this.checked) {
                window.dispatchEvent(new CustomEvent('open-modal-modal-default'));
            } else {
                window.dispatchEvent(new CustomEvent('close-modal-modal-default'));
            }
        })
    </script>
@endisset

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.dropify').dropify();
        $('#full_description').summernote();
        // $('.input-images-1').imageUploader();



        // increment
        $(document).on('click', '#add', function(e) {
            let htmlData = '<div class="flex gap-1 mt-2">';
            htmlData +=
                '<input type="file" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" accept="image/*" name="images[]" required>';

            htmlData += '<div id="remove" class="cursor-context-menu">';
            htmlData += '<span class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 bg-slate-100 text-sm">Remove</span>';
            htmlData += '</div>';
            htmlData += '</div>';
            $('#increment').append(htmlData);
        });
        // increment
        $(document).on('change', '#select_color', function(e) {
            let colors = $(this).val();
            var color = colors.split(',');

            let htmlData = '<div class="flex gap-1 mt-2">';
            htmlData += ' <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="hidden" name="colors[]"  readonly value="' +
                color[1] + '">';
            htmlData += ' <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="text"    readonly value="' + color[0] +
            '">';
            htmlData +=
                ' <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="number" placeholder="extra price" name="color_prices[]" value="">';
            htmlData +=
                ' <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm" type="number" placeholder="extra quantity" name="color_quantits[]" value="">';

            htmlData += '<div id="remove" class="cursor-context-menu">';
            htmlData += '<span class="inline-flex items-center px-3 py-2 rounded-md border border-slate-300 bg-slate-100 text-sm">Remove</span>';
            htmlData += '</div>';
            htmlData += '</div>';
            $('#increment_color').append(htmlData);
        });
        // remove
        $(document).on('click', '#remove', function(e) {
            $(this).parent().remove();
        });

        // increment file
        $(document).on('click', '#add-file', function(e) {
            let htmlData = '<div class="flex flex-wrap gap-2 mt-2">';
            htmlData +=
                '<div class="w-full md:w-5/12"><input type="text" name="file_name[]" id="" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="Enter file name"></div>';
            htmlData +=
                '<div class="w-full md:w-5/12"><input type="text" name="file_url[]" id="" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm" placeholder="Enter file url"></div>';
            htmlData +=
                '<div class="w-full md:w-auto"><input type="file" name="files[]" id="" class="custom-file"></div>';
            htmlData += '<div class="w-full md:w-auto">';
            htmlData += '<input type="hidden" name="ids[]" value="0">';
            htmlData +=
                '<button type="button" data-id="0" id="remove-file" class="inline-flex items-center gap-1 rounded-md bg-danger px-3 py-2 text-sm font-medium text-white hover:opacity-90"><i class="fa fa-trash-alt"></i></button></div>';
            htmlData += '</div>';

            $('#increment-file').append(htmlData);
        });

        // remove file
        $(document).on('click', '#remove-file', function(e) {
            e.preventDefault();
            let btn = $(this);
            let id = $(this).data('id');

            if (id == 0) {
                $(this).parent().parent().remove();
            } else {
                $.ajax({
                    type: 'GET',
                    url: '/vendor/delete/product/download/' + id,
                    dataType: "JSON",
                    beforeSend: function() {
                        $(btn).addClass('disabled');
                    },
                    success: function(response) {
                        $(btn).parent().parent().remove();
                    },
                    complete: function() {
                        $(btn).removeClass('disabled');
                    }
                });
            }

        });

        $(document).on('change', '#category', function() {

            var options = document.getElementById('category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);

            $.ajax({
                type: 'POST',
                url: '/vendor/get/sub-categories',
                data: {
                    'ids': values,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {

                    let data = '<option value="">Select Sub Category</option>';
                    $.each(response, function(key, val) {
                        data += '<option value="' + val.id + '">' + val.name +
                            '</option>';

                    });
                    $('#sub_category').html(data).attr('multiple', true).select2();
                }
            });
        });
        $(document).on('change', '#sub_category', function() {

            var options = document.getElementById('sub_category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);

            $.ajax({
                type: 'POST',
                url: '/vendor/get/mini-categories',
                data: {
                    'ids': values,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {

                    let data = '<option value="">Select Sub Category</option>';
                    $.each(response, function(key, val) {
                        data += '<option value="' + val.id + '">' + val.name +
                            '</option>';

                    });
                    $('#mini_category').html(data).attr('multiple', true).select2();
                }
            });
        });
        $(document).on('change', '#mini_category', function() {

            var options = document.getElementById('mini_category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);

            $.ajax({
                type: 'POST',
                url: '/vendor/get/extra-categories',
                data: {
                    'ids': values,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {

                    let data = '<option value="">Select Sub Category</option>';
                    $.each(response, function(key, val) {
                        data += '<option value="' + val.id + '">' + val.name +
                            '</option>';

                    });
                    $('#extra_category').html(data).attr('multiple', true).select2();
                }
            });
        });
    });
</script>
@isset($product)
    <script>
        function productImages() {

            let id = '{!! $product->id !!}';
            console.log(id);
            $.ajax({
                type: 'GET',
                url: '/vendor/get/product/image/' + id,
                dataType: 'JSON',
                success: function(response) {

                    let preloaded = [];
                    $.each(response, function(key, val) {
                        preloaded.push({
                            id: val.id,
                            src: '/uploads/product/' + val.name
                        });
                    });

                    $('.input-images-1').imageUploader({
                        preloaded: preloaded,
                        imagesInputName: 'photos',
                        preloadedInputName: 'old'
                    });
                }
            });
        }

        function attributes() {
            var options = document.getElementById('category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);
            var product_id = $('#id').val();

            $.ajax({
                type: 'POST',
                url: '/admin/get/attributes',
                data: {
                    'ids': values,
                    'product_id': product_id,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {
                    $('#sho_attributes').html(response);
                }
            });
        }
        attributes();
        $(document).on('change', '#category', function() {

            var options = document.getElementById('category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);
            var product_id = $('#id').val();
            $.ajax({
                type: 'POST',
                url: '/vendor/get/attributes',
                data: {
                    'ids': values,
                    'product_id': product_id,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {
                    $('#sho_attributes').html(response);
                }
            });
        });
    </script>
@else
    <script>
        $(document).on('change', '#category', function() {

            var options = document.getElementById('category').selectedOptions;
            var values = Array.from(options).map(({
                value
            }) => value);

            $.ajax({
                type: 'POST',
                url: '/vendor/get/attributes',
                data: {
                    'ids': values,
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "JSON",
                success: function(response) {
                    $('#sho_attributes').html(response);
                }
            });
        });



        // Dicount required while change discount type
        $(document).on('change', '#dis_type', function(e) {
            // Check if the selected value is not equal to 0
            if ($(this).val() != "0") {
                // Make discount_price input required
                $('#discount_price').prop('required', true);
            } else {
                // Make discount_price input not required
                $('#discount_price').prop('required', false);
            }
        });
    </script>
@endisset
@endpush

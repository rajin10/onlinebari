@extends('layouts.admin.app')

@section('title', 'Settings')


@section('content')

    {{-- Content Header --}}
    <section class="mb-4">
        <div>
            <div class="flex flex-wrap items-center mb-2">
                <div class="w-full md:w-1/2">
                    <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Layout</small></h1>
                </div>
                <div class="w-full md:w-1/2">
                    <ol class="flex flex-wrap items-center gap-1 text-sm text-slate-500 md:justify-end">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li class="before:content-['/'] before:mx-1">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <section class="mb-6">
        <x-ui.card>
            <x-slot:header>Application Layout</x-slot:header>

            <div class="flex justify-center">
                <div class="w-10/12 mx-auto">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-green-700 bg-green-700 px-4 py-3 font-medium text-white rounded-t-lg">
                            Setting - Layout Change
                        </div>
                        <form id="layoutForm" action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="9">
                            <div class="p-4">
                                <ul class="flex flex-wrap list-none p-0 m-0">
                                    <li class="w-full bg-slate-100 px-2 py-2 mb-0 font-bold">Global Layout</li>
                                    <li class="w-full mb-4 px-1 py-2">
                                        <label for="TOP_HEADER_STYLE" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Top Header Style: </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary w-1/4 cursor-pointer" name="TOP_HEADER_STYLE" id="TOP_HEADER_STYLE">
                                            <option class="text-white bg-success"
                                                value="{{ $TOP_HEADER_STYLE->value }}">Style
                                                {{ $TOP_HEADER_STYLE->value }}</option>
                                            <option value="1">Style 1</option>
                                            <option value="2">Style 2</option>
                                            <option value="3">Style 3</option>
                                        </select>
                                    </li>
                                    <div
                                        class="w-10/12 mx-auto px-3 bg-slate-100 border border-info {{ $TOP_HEADER_STYLE->value == 3 ? 'block' : 'hidden' }}">
                                        <li class="w-full mb-4 px-1 py-2">
                                            <label for="STYLE_3_TOP_MENU" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Style 3 top menu add:
                                            </label>
                                            <br>
                                            <textarea class="rounded-md border border-dashed border-green-800 px-2 py-1 outline-none w-3/4"
                                                name="STYLE_3_TOP_MENU"
                                                id="STYLE_3_TOP_MENU" cols="30" rows="3">{{ $STYLE_3_TOP_MENU->value }}</textarea>
                                            <br>
                                            <div class="w-3/4">
                                                <small>Copy the menu code and paste in above box and customized as per
                                                    your rquiements (Multi-menu add multi li and a tag):</small>
                                                <script src="https://gist.github.com/finvasoft/c380eaf18b41491d650c28f152ce79a4.js"></script>
                                            </div>
                                        </li>
                                        <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1 capitalize" for="STYLE_3_TOP_MENU_BG_COLOR">Style
                                                3 Top Menu Background Color: </label>
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 inline-block cursor-pointer" type="color"
                                                id="STYLE_3_TOP_MENU_BG_COLOR_CHOOSER"
                                                value="{{ $STYLE_3_TOP_MENU_BG_COLOR->value }}">
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 inline-block" type="text"
                                                id="STYLE_3_TOP_MENU_BG_COLOR" name="STYLE_3_TOP_MENU_BG_COLOR"
                                                value="{{ $STYLE_3_TOP_MENU_BG_COLOR->value }}">
                                        </li>
                                        <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1 capitalize"
                                                for="STYLE_3_TOP_MENU_LINK_COLOR">Style 3 Top Menu Link Color: </label>
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 inline-block cursor-pointer" type="color"
                                                id="STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER"
                                                value="{{ $STYLE_3_TOP_MENU_LINK_COLOR->value }}">
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 inline-block" type="text"
                                                id="STYLE_3_TOP_MENU_LINK_COLOR" name="STYLE_3_TOP_MENU_LINK_COLOR"
                                                value="{{ $STYLE_3_TOP_MENU_LINK_COLOR->value }}">
                                        </li>
                                        <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1 capitalize"
                                                for="STYLE_3_TOP_MENU_LINK_HOVER_COLOR">Style 3 Top Menu Link Hover
                                                Color: </label>
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 inline-block cursor-pointer" type="color"
                                                id="STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER"
                                                value="{{ $STYLE_3_TOP_MENU_LINK_HOVER_COLOR->value }}">
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 inline-block" type="text"
                                                id="STYLE_3_TOP_MENU_LINK_HOVER_COLOR"
                                                name="STYLE_3_TOP_MENU_LINK_HOVER_COLOR"
                                                value="{{ $STYLE_3_TOP_MENU_LINK_HOVER_COLOR->value }}">
                                        </li>
                                        <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                            <label class="block text-sm font-medium text-slate-700 mb-1 capitalize"
                                                for="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT">Style 3 Search Input Width:
                                            </label>
                                            <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 inline-block" type="text"
                                                id="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT"
                                                name="STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT"
                                                value="{{ setting('STYLE_3_HEADER_SEARCH_INPUT_BAR_WIDHT') ?? '' }}">
                                        </li>
                                        @push('js')
                                            <script>
                                                $(document).ready(function() {
                                                    $("#STYLE_3_TOP_MENU_BG_COLOR_CHOOSER").on("input", function() {
                                                        $("#STYLE_3_TOP_MENU_BG_COLOR").val($(this).val());
                                                    });
                                                    $("#STYLE_3_TOP_MENU_BG_COLOR").on("keyup", function() {
                                                        $("#STYLE_3_TOP_MENU_BG_COLOR_CHOOSER").val($(this).val());
                                                    });
                                                    $("#STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER").on("input", function() {
                                                        $("#STYLE_3_TOP_MENU_LINK_COLOR").val($(this).val());
                                                    });
                                                    $("#STYLE_3_TOP_MENU_LINK_COLOR").on("keyup", function() {
                                                        $("#STYLE_3_TOP_MENU_LINK_COLOR_CHOOSER").val($(this).val());
                                                    });
                                                    $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER").on("input", function() {
                                                        $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR").val($(this).val());
                                                    });
                                                    $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR").on("keyup", function() {
                                                        $("#STYLE_3_TOP_MENU_LINK_HOVER_COLOR_CHOOSER").val($(this).val());
                                                    });
                                                });
                                            </script>
                                        @endpush
                                    </div>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="MAIN_MENU_STYLE" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Main Menu Style: </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="MAIN_MENU_STYLE" id="MAIN_MENU_STYLE">
                                            <option class="text-white bg-success"
                                                value="{{ $MAIN_MENU_STYLE->value }}">Style
                                                {{ $MAIN_MENU_STYLE->value }}</option>
                                            <option value="1">Style 1</option>
                                            <option value="2">Style 2</option>
                                            <option value="3">Style 3</option>
                                        </select>
                                    </li>

                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="placeholder_one" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Place Holder One</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-full" type="text" name="placeholder_one"
                                            id="placeholder_one"
                                            value="{{ setting('placeholder_one') ?? 'Search by product name' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="placeholder_two" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Place Holder Two</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-full" type="text" name="placeholder_two"
                                            id="placeholder_two"
                                            value="{{ setting('placeholder_two') ?? 'Search by Vendor' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="placeholder_three" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Place Holder
                                            Three</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-full" type="text" name="placeholder_three"
                                            id="placeholder_three"
                                            value="{{ setting('placeholder_three') ?? 'Search by category' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="placeholder_four" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Place Holder
                                            Four</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-full" type="text" name="placeholder_four"
                                            id="placeholder_four"
                                            value="{{ setting('placeholder_four') ?? 'Search by product' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="FLOAT_LIVE_CHAT" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Float Live Chat: </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 cursor-pointer" name="FLOAT_LIVE_CHAT"
                                            id="FLOAT_LIVE_CHAT">
                                            <option class="text-white bg-success"
                                                value="{{ $FLOAT_LIVE_CHAT->value }}">
                                                {{ $FLOAT_LIVE_CHAT->value == 1 ? 'Live Chat' : 'WhatsApp' }}
                                            </option>
                                            <option value="1">Live Chat</option>
                                            <option value="0">WhatsApp</option>
                                        </select>
                                    </li>
                                </ul>
                                <ul class="flex flex-wrap list-none p-0 m-0">
                                    <li class="w-full bg-slate-100 px-2 py-2 mb-0 font-bold">Home Components Layout</li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="SLIDER_LAYOUT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Feature Products
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/2 cursor-pointer" name="SLIDER_LAYOUT_STATUS"
                                            id="SLIDER_LAYOUT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $SLIDER_LAYOUT_STATUS->value }}">
                                                {{ $SLIDER_LAYOUT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="SLIDER_LAYOUT" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Slider Layout: </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-3/4 cursor-pointer" name="SLIDER_LAYOUT" id="SLIDER_LAYOUT">
                                            <option class="text-white bg-success"
                                                value="{{ $SLIDER_LAYOUT->value }}">Style {{ $SLIDER_LAYOUT->value }}
                                            </option>
                                            <option value="1">Style 1 - Container</option>
                                            <option value="2">Style 2 - Full Width</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label class="inline text-sm font-medium text-slate-700 capitalize" for="HERO_SLIDER_1">Hero Slider 1:
                                        </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 inline cursor-pointer" name="HERO_SLIDER_1"
                                            id="HERO_SLIDER_1">
                                            <option class="text-white bg-success"
                                                value="{{ $HERO_SLIDER_1->value }}">
                                                {{ $HERO_SLIDER_1->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                        <br><br>
                                        <label class="inline text-sm font-medium text-slate-700 capitalize" for="HERO_SLIDER_2">Hero Slider 2:
                                        </label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 inline cursor-pointer" name="HERO_SLIDER_2"
                                            id="HERO_SLIDER_2">
                                            <option class="text-white bg-success"
                                                value="{{ $HERO_SLIDER_2->value }}">
                                                {{ $HERO_SLIDER_2->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="HERO_SLIDER_1_TEXT" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Hero Slider 1
                                            Title</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-3/4" type="text" name="HERO_SLIDER_1_TEXT"
                                            value="{{ setting('HERO_SLIDER_1_TEXT') ?? '' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">

                                    </li>
                                </ul>
                                <ul class="flex flex-wrap list-none p-0 m-0">
                                    <li class="w-full bg-slate-100 px-2 py-2 mb-0 font-bold">Home Layout</li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="TOP_CAT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Top Category
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="TOP_CAT_STATUS" id="TOP_CAT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $TOP_CAT_STATUS->value }}">
                                                {{ $TOP_CAT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                        <br>
                                        <label for="TOP_CAT" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Top Category Title</label>
                                        <input class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-3/4" type="text"
                                            name="TOP_CAT" value="{{ setting('TOP_CAT') ?? '' }}">
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="SELLER_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Seller Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="SELLER_STATUS" id="SELLER_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $SELLER_STATUS->value }}">
                                                {{ $SELLER_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="LATEST_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Latest Products
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="LATEST_PRODUCT_STATUS"
                                            id="LATEST_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $LATEST_PRODUCT_STATUS->value }}">
                                                {{ $LATEST_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="FEATURE_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Feature Products
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="FEATURE_PRODUCT_STATUS"
                                            id="FEATURE_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $FEATURE_PRODUCT_STATUS->value }}">
                                                {{ $FEATURE_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="CLASSIFIED_SELL_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Classified Sell
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="CLASSIFIED_SELL_STATUS"
                                            id="CLASSIFIED_SELL_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $CLASSIFIED_SELL_STATUS->value }}">
                                                {{ $CLASSIFIED_SELL_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="MEGA_CAT_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">MEGA Cateogory
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="MEGA_CAT_PRODUCT_STATUS"
                                            id="MEGA_CAT_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $MEGA_CAT_PRODUCT_STATUS->value }}">
                                                {{ $MEGA_CAT_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="SUB_CAT_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Sub Cateogory
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="SUB_CAT_PRODUCT_STATUS"
                                            id="SUB_CAT_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $SUB_CAT_PRODUCT_STATUS->value }}">
                                                {{ $SUB_CAT_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="MINI_CAT_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Mini Category
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="MINI_CAT_PRODUCT_STATUS"
                                            id="MINI_CAT_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $MINI_CAT_PRODUCT_STATUS->value }}">
                                                {{ $MINI_CAT_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="EXTRA_CAT_PRODUCT_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Extra Category
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="EXTRA_CAT_PRODUCT_STATUS"
                                            id="EXTRA_CAT_PRODUCT_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $EXTRA_CAT_PRODUCT_STATUS->value }}">
                                                {{ $EXTRA_CAT_PRODUCT_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="BRAND_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Brand Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="BRAND_STATUS" id="BRAND_STATUS">
                                            <option class="text-white bg-success" value="{{ $BRAND_STATUS->value }}">
                                                {{ $BRAND_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="CATEGORY_SMALL_SUMMERY" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Cateogry Small
                                            Summery Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="CATEGORY_SMALL_SUMMERY"
                                            id="CATEGORY_SMALL_SUMMERY">
                                            <option class="text-white bg-success"
                                                value="{{ $CATEGORY_SMALL_SUMMERY->value }}">
                                                {{ $CATEGORY_SMALL_SUMMERY->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                    <li class="w-full md:w-1/2 mb-4 px-1 py-2">
                                        <label for="NEWS_LETTER_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">News Letter
                                            Status</label>
                                        <select class="rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm w-1/4 cursor-pointer" name="NEWS_LETTER_STATUS"
                                            id="NEWS_LETTER_STATUS">
                                            <option class="text-white bg-success"
                                                value="{{ $NEWS_LETTER_STATUS->value }}">
                                                {{ $NEWS_LETTER_STATUS->value == 1 ? 'On' : 'Off' }}</option>
                                            <option value="1">On</option>
                                            <option value="0">Off</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div class="border-t border-slate-200 px-4 py-3">
                                <x-ui.button type="submit" variant="success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </section>

@endsection

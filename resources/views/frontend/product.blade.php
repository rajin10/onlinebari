@extends('layouts.frontend.app')

@push('meta')
<meta name='description' content="All Products"/>
<meta name='keywords' content="@foreach($products as $product){{$product->title.', '}}@endforeach" />
@endpush

@section('title', 'Shop')

@push('css')
    <link rel="stylesheet" href="{{asset('/')}}assets/frontend/css/jquery-ui1.css">
@endpush

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── HERO ── */
    .sp-hero {
        padding: 56px 24px 42px;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .sp-hero-eyebrow {
        margin: 0 0 12px;
        font-size: 11px;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #888;
        font-weight: 500;
    }
    .sp-hero-title {
        margin: 0;
        font-family: 'Cinzel Decorative', Georgia, serif;
        font-size: 44px;
        font-weight: 700;
        color: #111;
        line-height: 1;
        text-transform: uppercase;
    }

    /* ── FULL-WIDTH LAYOUT ── */
    .sp-layout {
        display: flex;
        width: 100%;
        align-items: flex-start;
        background: #fff;
    }

    /* ── SIDEBAR ── */
    .sp-sidebar {
        width: 210px;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        background: #f9f9f9;
        border-right: 1px solid #ebebeb;
        scrollbar-width: thin;
        scrollbar-color: #ddd #f9f9f9;
        transition: transform .3s ease;
    }
    .sp-sidebar::-webkit-scrollbar { width: 4px; }
    .sp-sidebar::-webkit-scrollbar-track { background: #f9f9f9; }
    .sp-sidebar::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }
    .sp-sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 998;
    }
    .sp-sidebar-overlay.open { display: block; }
    .sp-sidebar-inner { padding: 18px 14px 32px; }
    .sp-sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e8e8e8;
    }
    .sp-sidebar-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #111;
    }
    .sp-sidebar-close {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        color: #888;
        font-size: 16px;
        line-height: 1;
        padding: 2px;
    }

    /* Sidebar filter styles — compact */
    .sp-sidebar-inner .range { margin-bottom: 14px; }
    .sp-sidebar-inner .dropdown-menu6 { list-style: none; padding: 0; margin: 0; }
    .sp-sidebar-inner .left-side { margin-bottom: 12px; }
    .sp-sidebar-inner .left-side > p,
    .sp-sidebar-inner .left-side > p:first-child {
        font-size: 10px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #666 !important;
        border-top: 1px solid #ebebeb !important;
        padding-top: 12px !important;
        margin-bottom: 8px !important;
    }
    .sp-sidebar-inner ul { list-style: none; padding: 0; margin: 0; }
    .sp-sidebar-inner ul li { padding: 2px 0; }
    .sp-sidebar-inner ul li label,
    .sp-sidebar-inner .span { font-size: 12px; color: #444; }
    .sp-sidebar-inner .cck2 {
        display: inline-flex;
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 2px 10px;
        margin: 2px 2px;
        font-size: 11px;
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }
    .sp-sidebar-inner .cck2:hover { background: #f0f0f0; border-color: #bbb; }
    .sp-sidebar-inner .cck2.active { background: #111; color: #fff; border-color: #111; }
    .sp-sidebar-inner .cc label { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #444; padding: 3px 0; cursor: pointer; }
    .sp-sidebar-inner .cc label input { accent-color: #111; }
    .sp-sidebar-inner hr { border: none; border-top: 1px solid #ebebeb; margin: 12px 0; }
    .sp-sidebar-inner .action ul li { display: inline-block; margin-right: 6px; margin-top: 8px; }
    .sp-sidebar-inner .action ul li input[type="submit"] {
        background: #111; color: #fff; border: none;
        padding: 8px 16px; border-radius: 7px;
        font-size: 11.5px; font-weight: 600; cursor: pointer; letter-spacing: .5px;
    }
    .sp-sidebar-inner .action ul li a {
        display: inline-block; background: #fff; color: #555;
        border: 1px solid #ddd; padding: 7px 14px; border-radius: 7px;
        font-size: 11.5px; font-weight: 500; text-decoration: none;
    }
    .sp-sidebar-inner #slider-range { margin: 6px 2px; }
    .sp-sidebar-inner #amount {
        width: 100%; background: transparent; border: none;
        color: #666; font-size: 11.5px; padding: 4px 0;
    }

    /* ── MAIN AREA ── */
    .sp-main {
        flex: 1;
        min-width: 0;
        padding: 36px 44px 72px 36px;
        background: #fff;
    }

    /* Mobile topbar */
    .sp-topbar {
        display: none;
        align-items: center;
        margin-bottom: 20px;
    }
    .sp-filter-btn {
        display: flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 500;
        color: #333;
        cursor: pointer;
        transition: all .2s;
    }
    .sp-filter-btn:hover { background: #f5f5f5; }

    /* ── CATEGORY SECTIONS ── */
    .sp-cat-section {
        margin-bottom: 60px;
    }
    .sp-cat-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0f0f0;
    }
    .sp-cat-name {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #111;
        letter-spacing: -0.2px;
    }
    .sp-cat-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        color: #888;
        text-decoration: none;
        letter-spacing: 0.3px;
        transition: color .2s, gap .2s;
    }
    .sp-cat-link:hover { color: #111; gap: 8px; }

    /* ── SLICK CAROUSEL ── */
    .sp-slider { position: relative; }
    .sp-slider .slick-list { overflow: hidden; }
    .sp-slider .slick-slide { padding: 0 10px; }
    .sp-slider .slick-track { display: flex; }

    /* Lux card height inside shop carousel */
    .sp-slider .lux-product-thumb { height: 380px !important; }
    .sp-slider .lux-product-name {
        font-size: 15px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
    }

    /* Custom arrows */
    .sp-slider .slick-prev,
    .sp-slider .slick-next {
        width: 38px;
        height: 38px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 50%;
        z-index: 2;
        top: 38%;
        transform: translateY(-50%);
        box-shadow: 0 3px 12px rgba(0,0,0,.09);
        transition: all .2s ease;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    .sp-slider .slick-prev { left: -18px; }
    .sp-slider .slick-next { right: -18px; }
    .sp-slider .slick-prev:hover,
    .sp-slider .slick-next:hover {
        background: #111;
        border-color: #111;
    }
    .sp-slider .slick-prev:before { content: '←'; }
    .sp-slider .slick-next:before { content: '→'; }
    .sp-slider .slick-prev:before,
    .sp-slider .slick-next:before {
        font-size: 14px;
        color: #666;
        font-family: inherit;
        line-height: 1;
        opacity: 1;
    }
    .sp-slider .slick-prev:hover:before,
    .sp-slider .slick-next:hover:before { color: #fff; }
    .sp-slider .slick-disabled { opacity: 0.3; pointer-events: none; }

    /* ── LIST VIEW (hidden) ── */
    #sp-list-view { padding: 0; }

    /* ── AJAX SKELETON ── */
    .sp-skeleton { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
    .sp-ske-img { height: 300px; border-radius: 10px; }
    .sp-ske-line { height: 13px; border-radius: 5px; }
    @keyframes sp-pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
    .sp-ske-pulse { background: #eee; animation: sp-pulse 1.5s ease infinite; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1200px) {
        .sp-slider .lux-product-thumb { height: 320px !important; }
        .sp-main { padding: 28px 28px 56px 24px; }
    }
    @media (max-width: 900px) {
        .sp-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            transform: translateX(-100%);
            border-right: none;
            box-shadow: 4px 0 28px rgba(0,0,0,.14);
        }
        .sp-sidebar.open { transform: translateX(0); }
        .sp-sidebar-close { display: block; }
        .sp-layout { flex-direction: column; }
        .sp-main { padding: 20px 20px 56px; width: 100%; }
        .sp-topbar { display: flex; }
        .sp-slider .lux-product-thumb { height: 280px !important; }
    }
    @media (max-width: 600px) {
        .sp-hero-title { font-size: 30px; }
        .sp-hero { padding: 40px 20px 32px; }
        .sp-slider .lux-product-thumb { height: 240px !important; }
        .sp-cat-name { font-size: 17px; }
    }
    @media (max-width: 420px) {
        .sp-slider .lux-product-thumb { height: 210px !important; }
    }
</style>

{{-- ====== HERO ====== --}}
<div class="sp-hero">
    <p class="sp-hero-eyebrow">Curated Collection</p>
    <h1 class="sp-hero-title">All Products</h1>
</div>

{{-- ====== LAYOUT ====== --}}
<div class="sp-layout">

    {{-- SIDEBAR --}}
    <aside class="sp-sidebar" id="spSidebar">
        <div class="sp-sidebar-inner">
            <div class="sp-sidebar-header">
                <span class="sp-sidebar-title">Filters</span>
                <button class="sp-sidebar-close" id="spSidebarClose" aria-label="Close">✕</button>
            </div>
            <x-filter-search-component />
        </div>
    </aside>
    <div class="sp-sidebar-overlay" id="spOverlay"></div>

    {{-- MAIN --}}
    <div class="sp-main">

        {{-- Mobile filter toggle --}}
        <div class="sp-topbar">
            <button class="sp-filter-btn" id="spFilterBtn">
                <svg width="15" height="11" viewBox="0 0 15 11" fill="none">
                    <path d="M0 1h15M3 5.5h9M6 10h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Filters
            </button>
        </div>

        {{-- Group products by category and render each as a carousel section --}}
        @php
            $spGrouped = [];
            foreach ($products as $product) {
                $cat = $product->categories->first();
                $key  = $cat ? $cat->name : 'Featured';
                $slug = $cat ? $cat->slug  : '';
                if (!isset($spGrouped[$key])) {
                    $spGrouped[$key] = ['slug' => $slug, 'items' => []];
                }
                $spGrouped[$key]['items'][] = $product;
            }
        @endphp

        {{-- CAROUSEL SECTIONS --}}
        <div id="grid-view">
            @forelse ($spGrouped as $catName => $group)
            <div class="sp-cat-section">
                <div class="sp-cat-header">
                    <h2 class="sp-cat-name">{{ $catName }}</h2>
                    @if (!empty($group['slug']))
                    <a href="{{ route('category.product', $group['slug']) }}" class="sp-cat-link">
                        View All
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    @endif
                </div>
                <div class="sp-slider">
                    @foreach ($group['items'] as $product)
                        <x-lux-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
            @empty
                <x-product-empty-component />
            @endforelse
        </div>

        {{-- LIST VIEW (hidden, toggle available) --}}
        <div id="list-view" style="display:none;">
            @forelse ($products as $product)
                <x-product-list-view :product="$product" />
            @empty
                <x-product-empty-component />
            @endforelse
        </div>

        {{-- AJAX skeleton --}}
        <div class="load ajax-loading sp-skeleton" style="display:none;">
            <div class="sp-ske-img sp-ske-pulse"></div>
            <div class="sp-ske-line sp-ske-pulse" style="width:55%"></div>
            <div class="sp-ske-line sp-ske-pulse" style="width:75%"></div>
            <div class="sp-ske-line sp-ske-pulse" style="width:40%"></div>
        </div>

    </div>
</div>

<x-add-cart-modal />
@include('components.cart-modal-attri')

@endsection

@push('js')
    <script src="{{asset('/')}}assets/frontend/js/jquery-ui.js"></script>
    <script>
    $(document).ready(function () {

        // ── Init all carousels ──
        $('.sp-slider').each(function() {
            var $slider = $(this);
            var count   = $slider.children().length;
            $slider.slick({
                slidesToShow:   Math.min(4, count),
                slidesToScroll: 1,
                arrows:         true,
                dots:           false,
                infinite:       false,
                speed:          420,
                cssEase:        'ease',
                responsive: [
                    { breakpoint: 1400, settings: { slidesToShow: Math.min(3, count) } },
                    { breakpoint: 900,  settings: { slidesToShow: Math.min(2, count) } },
                    { breakpoint: 540,  settings: { slidesToShow: 1 } }
                ]
            });
        });

        // ── Price range slider ──
        $("#slider-range").slider({
            range:  true,
            min:    0,
            max:    9000,
            values: [50, 6000],
            slide:  function (event, ui) {
                $("#amount").val("{!! setting('CURRENCY_CODE_MIN') ?? 'TK' !!}" + ui.values[0] + " - {!! setting('CURRENCY_CODE_MIN') ?? 'TK' !!}" + ui.values[1]);
            }
        });
        $("#amount").val(
            "{!! setting('CURRENCY_CODE_MIN') ?? 'TK' !!}" + $("#slider-range").slider("values", 0) +
            " - {!! setting('CURRENCY_CODE_MIN') ?? 'TK' !!}" + $("#slider-range").slider("values", 1)
        );

        // ── Qty buttons ──
        $('.value-plus').on('click', function () {
            var d = $(this).parent().find('.value'), v = parseInt(d.val(), 10) + 1;
            d.val(v); $('input#qty').val(v);
        });
        $('.value-minus').on('click', function () {
            var d = $(this).parent().find('.value'), v = parseInt(d.val(), 10) - 1;
            if (v >= 1) { d.val(v); $('input#qty').val(v); }
        });

        // ── Cart modal form ──
        $(document).on('submit', '#addToCart', function (e) {
            e.preventDefault();
            var url = $(this).attr('action'), type = $(this).attr('method'),
                btn = $(this), formData = $(this).serialize();
            $.ajax({
                type: type, url: url, data: formData, dataType: 'JSON',
                beforeSend: function () { $(btn).attr('disabled', true); },
                success: function (response) {
                    if (response.alert != 'Congratulations') {
                        $.toast({ heading: 'Warning', text: response.message, icon: 'warning', position: 'top-right', stack: false });
                    } else {
                        loadCartOnCanvas();
                        $('span#total-cart-amount').text(response.subtotal);
                        $.toast({ heading: 'Congratulations', text: response.message, icon: 'success', position: 'top-right', stack: false });
                        $('#cart-modal').modal('hide');
                    }
                },
                complete: function () { $(btn).attr('disabled', false); },
                error: function (xhr) {
                    $.toast({ heading: xhr.status, text: xhr.responseJSON?.message, icon: 'error', position: 'top-right', stack: false });
                }
            });
        });

        // ── Sort select ──
        $(document).on('change', 'select#sort', function () {
            $('input[name="sort"]').val($(this).val());
            $('form#form').submit();
        });

        // ── Mobile sidebar ──
        $('#spFilterBtn').on('click', function () {
            $('#spSidebar').addClass('open');
            $('#spOverlay').addClass('open');
        });
        $('#spSidebarClose, #spOverlay').on('click', function () {
            $('#spSidebar').removeClass('open');
            $('#spOverlay').removeClass('open');
        });

        // ── Filter attribute pills ──
        $('.cck2').click(function () { $(this).toggleClass('active'); });
        $('.sub_mod').click(function () {
            if ($(this).is(':checked')) { $('#dcd').prop('checked', false); }
        });
    });

    // Lux add-to-cart + Order Now are handled globally in layouts/frontend/app.blade.php
    </script>

@if(isset($slug))
<script>
    var site_url = "{{ url('/') }}"; var page = 1;
    function load_more(page) {
        var slug = '{!! $slug !!}';
        var _total = $(".lux-product-card").length;
        $.ajax({
            url: site_url + "/brand/" + slug + "?page=" + page, type: "get", datatype: "html",
            data: { skip: _total },
            beforeSend: function () { $('.ajax-loading').show(); },
            success: function (response) {
                var result = $.parseJSON(response);
                $('.ajax-loading').hide();
                $("#grid-view").append(result[0]);
                $("#list-view").append(result[1]);
            }
        });
    }
</script>
@else
<script>
    var site_url = "{{ url('/') }}"; var page = 1;
    function load_more(page) {
        var _total = $(".lux-product-card").length;
        $.ajax({
            url: site_url + "/product?page=" + page, type: "get", datatype: "html",
            data: { skip: _total },
            beforeSend: function () { $('.ajax-loading').show(); },
            success: function (response) {
                var result = $.parseJSON(response);
                $('.ajax-loading').hide();
                $("#grid-view").append(result[0]);
                $("#list-view").append(result[1]);
            }
        });
    }
</script>
@endif
@endpush

@extends('layouts.frontend.app')
@push('meta')
<meta property="og:image" content="{{asset('uploads/setting/'.setting('auth_logo'))}}" />
@endpush

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── HERO ── */
    .cat-hero {
        padding: 60px 24px 48px;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .cat-hero-eyebrow {
        margin: 0 0 14px;
        font-size: 11px;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #888;
        font-weight: 500;
    }
    .cat-hero-title {
        margin: 0 0 12px;
        font-family: 'Cinzel Decorative', Georgia, serif;
        font-size: 44px;
        font-weight: 700;
        color: #111;
        line-height: 1;
        text-transform: uppercase;
    }
    .cat-hero-sub {
        margin: 0;
        font-size: 14px;
        color: #999;
        letter-spacing: 0.3px;
    }

    /* ── SECTION WRAPPER ── */
    .cat-section {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .cat-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #888;
        margin: 0 0 28px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .cat-section-title::before,
    .cat-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8e8e8;
    }

    /* ── CATEGORIES GRID ── */
    .cat-page-wrap {
        padding: 52px 0 24px;
        background: #fff;
    }
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    .cat-card {
        display: block;
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        background: #f5f5f5;
        text-decoration: none;
        aspect-ratio: 3/4;
        transition: transform .35s ease, box-shadow .35s ease;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .cat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,.14);
    }
    .cat-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .55s ease;
    }
    .cat-card:hover img { transform: scale(1.06); }
    .cat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.18) 50%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    .cat-card-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 18px;
        z-index: 2;
        color: #fff;
    }
    .cat-card-name {
        display: block;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        line-height: 1.3;
        margin-bottom: 4px;
        letter-spacing: 0.2px;
    }
    .cat-card-arrow {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: rgba(255,255,255,.65);
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 500;
        transition: color .2s, gap .2s;
    }
    .cat-card:hover .cat-card-arrow {
        color: #fff;
        gap: 8px;
    }
    .cat-card-arrow svg { transition: transform .2s; }
    .cat-card:hover .cat-card-arrow svg { transform: translateX(3px); }

    /* ── COLLECTIONS ── */
    .col-page-wrap {
        padding: 48px 0 64px;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
    }
    .col-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    .col-card {
        display: block;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        border: 1px solid #ebebeb;
        transition: all .3s ease;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }
    .col-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(0,0,0,.10);
        border-color: #ddd;
    }
    .col-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        background: #f5f5f5;
        transition: transform .4s ease;
    }
    .col-card:hover .col-card-img { transform: scale(1.04); }
    .col-card-body {
        padding: 16px;
        overflow: hidden;
    }
    .col-card-name {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #111;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .col-card-count {
        font-size: 12px;
        color: #999;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
        .cat-grid { grid-template-columns: repeat(3, 1fr); }
        .col-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .cat-hero-title { font-size: 30px; }
        .cat-hero { padding: 40px 20px 32px; }
        .cat-section { padding: 0 16px; }
        .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .col-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .cat-page-wrap, .col-page-wrap { padding-top: 36px; }
    }
    @media (max-width: 480px) {
        .cat-hero-title { font-size: 24px; }
        .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .col-grid { grid-template-columns: repeat(2, 1fr); }
        .col-card-img { height: 150px; }
    }
</style>

{{-- ====== HERO ====== --}}
<div class="cat-hero">
    <p class="cat-hero-eyebrow">Explore the Collection</p>
    <h1 class="cat-hero-title">Browse Categories</h1>
    <p class="cat-hero-sub">Find exactly what you're looking for across all our curated categories</p>
</div>


{{-- ====== CATEGORIES ====== --}}
@if (setting('TOP_CAT_STATUS') != 0 || setting('TOP_CAT_STATUS') == "")
<div class="cat-page-wrap">
    <div class="cat-section">
        <p class="cat-section-title">All Categories</p>
        <div class="cat-grid">

            @foreach ($categories_f as $category)
            <a href="{{ route('category.product', $category->slug) }}" class="cat-card">
                <img src="{{ asset('uploads/category/' . $category->cover_photo) }}" alt="{{ $category->name }}">
                <div class="cat-card-info">
                    <span class="cat-card-name">{{ $category->name }}</span>
                    <span class="cat-card-arrow">
                        Shop
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach

            @foreach ($mini_f as $category)
            <a href="{{ route('miniCategory.product', $category->slug) }}" class="cat-card">
                <img src="{{ asset('uploads/mini-category/' . $category->cover_photo) }}" alt="{{ $category->name }}">
                <div class="cat-card-info">
                    <span class="cat-card-name">{{ $category->name }}</span>
                    <span class="cat-card-arrow">
                        Shop
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach

            @foreach ($sub_f as $category)
            <a href="{{ route('subCategory.product', $category->slug) }}" class="cat-card">
                <img src="{{ asset('uploads/sub category/' . $category->cover_photo) }}" alt="{{ $category->name }}">
                <div class="cat-card-info">
                    <span class="cat-card-name">{{ $category->name }}</span>
                    <span class="cat-card-arrow">
                        Shop
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach

        </div>
    </div>
</div>
@endif


{{-- ====== COLLECTIONS ====== --}}
@if (setting('CATEGORY_SMALL_SUMMERY') != 0 || setting('CATEGORY_SMALL_SUMMERY') == "")
@if(!empty($collections) && $collections->count())
<div class="col-page-wrap">
    <div class="cat-section">
        <p class="cat-section-title">Collections</p>
        <div class="col-grid">
            @foreach ($collections as $collection)
            @php
                $categoryIds = $collection->categories->pluck('id');
                $productIds  = DB::table('category_product')->whereIn('category_id', $categoryIds)->get()->pluck('product_id');
                $productCount = \App\Models\Product::whereIn('id', $productIds)->where('status', 1)->count();
            @endphp
            <a href="{{ route('collection.product', $collection->slug) }}" class="col-card">
                <img class="col-card-img" src="{{ asset('uploads/collection/' . $collection->cover_photo) }}" alt="{{ $collection->name }}">
                <div class="col-card-body">
                    <span class="col-card-name">{{ $collection->name }}</span>
                    <span class="col-card-count">{{ $productCount }} {{ Str::plural('product', $productCount) }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endif


{{-- SuperCategory collapse/expand (for header category menu) --}}
@push('internal_css')
.superCatHomeToggle{height:330px;overflow-y:hidden;}.superCatHomeToggle #superCatViewAll{bottom:0;}
#superCatViewAll{position:absolute;bottom:-1.5rem;left:0;right:0;background:var(--MAIN_MENU_BG);color:var(--MAIN_MENU_ul_li_color);z-index:999;outline:none;}
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var superCatElement = document.getElementById('superCat');
    if (!superCatElement) return;
    superCatElement.classList.add('superCatHomeToggle');
    superCatElement.addEventListener('mouseenter', function () { superCatElement.classList.remove('superCatHomeToggle'); });
    superCatElement.addEventListener('mouseleave', function () { superCatElement.classList.add('superCatHomeToggle'); });
});
</script>
@endpush

@endsection

@props(['product', 'category' => null])

@php
    // Resolve the primary product image (image column is a JSON array of filenames).
    $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
    if (! is_array($images)) {
        $images = ! empty($product->image) ? [$product->image] : [];
    }
    $mainImage = ! empty($images[0])
        ? asset('uploads/product/' . $images[0])
        : asset('frontend/images/placeholder.png');

    $price = $product->discount_price ?? $product->regular_price;

    // Section name wins (per-category sections), else the product's own category.
    $categoryName = $category
        ?? optional($product->categories->first())->name
        ?? 'Onlinebari';

    // Dynamic rating from the product's reviews.
    $reviewCount = $product->reviews->count();
    $avgRating   = $reviewCount > 0 ? $product->reviews->sum('rating') / $reviewCount : 0;
    $fullStars   = (int) round($avgRating);

    // Dynamic colour variants from the color_product pivot.
    $colors      = $product->colors;
    $maxSwatches = 4;
@endphp

{{-- ─────────────────────────────────────────────────────────────────────────
     Self-contained styles — pushed once per page regardless of card count.
     This makes the component work on any page without relying on homepage CSS.
──────────────────────────────────────────────────────────────────────────── --}}
@pushOnce('css')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── CARD WRAPPER ── */
    .lux-product-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    /* ── THUMBNAIL ── */
    .lux-product-thumb {
        position: relative;
        height: 540px;
        overflow: hidden;
        border-radius: 7px;
        background: #f5f5f5;
    }
    .lux-product-thumb a {
        display: block;
        width: 100%;
        height: 100%;
    }
    .lux-product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }
    .lux-product-card:hover .lux-product-thumb img {
        transform: scale(1.04);
    }

    /* ── INFO AREA ── */
    .lux-product-info {
        padding: 13px 0 0;
    }

    .lux-info-top-row,
    .lux-variants-row,
    .lux-purchase-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .lux-info-top-row {
        align-items: flex-start;
    }

    /* ── CATEGORY LABEL ── */
    .lux-category-label {
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
        color: #707070;
        text-transform: capitalize;
    }

    /* ── PRODUCT NAME ── */
    .lux-product-name {
        max-width: 210px;
        margin: 0;
        font-size: 19px;
        font-weight: 500;
        line-height: 1.16;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .lux-product-name a {
        color: #111;
        text-decoration: none;
    }
    .lux-product-name a:hover {
        color: #333;
    }

    /* ── WISHLIST BUTTON ── */
    .lux-wishlist-btn {
        margin-top: 3px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #000;
        font-size: 12px;
        line-height: 1.2;
        white-space: nowrap;
        cursor: pointer;
        flex-shrink: 0;
    }
    .lux-wishlist-btn:hover {
        color: #e74c3c;
    }

    /* ── VARIANTS ROW ── */
    .lux-variants-row {
        align-items: center;
        margin-top: 8px;
    }

    /* ── RATING ── */
    .lux-rating {
        color: #fbc831;
        font-size: 17px;
        line-height: 1;
        letter-spacing: -1px;
    }
    .lux-rating-count {
        margin-left: 4px;
        color: #707070;
        font-size: 12px;
    }

    /* ── COLOR SWATCHES ── */
    .lux-color-variants {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .lux-color {
        width: 19px;
        height: 19px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid #eee;
        flex-shrink: 0;
    }

    /* ── PURCHASE ROW ── */
    .lux-purchase-row {
        align-items: center;
        margin-top: 12px;
    }

    /* ── PRICE ── */
    .lux-product-price {
        margin: 0;
        color: #111;
        font-size: 19px;
        font-weight: 500;
    }

    /* ── CTA BUTTONS (premium) ── */
    .lux-cta-form { margin: 0; }

    .lux-cta-row {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .lux-add-to-cart,
    .lux-order-now {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 46px;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.2px;
        cursor: pointer;
        white-space: nowrap;
        transition: transform 0.2s ease, box-shadow 0.25s ease, background 0.25s ease, color 0.2s ease;
    }

    /* Secondary: Add to Cart (outline → fill on hover) */
    .lux-add-to-cart {
        border: 1.5px solid #1c1917;
        background: #fff;
        color: #1c1917;
    }
    .lux-add-to-cart:hover {
        background: #1c1917;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(28, 25, 23, 0.18);
    }
    .lux-add-to-cart svg { width: 17px; height: 17px; }

    /* Primary CTA: অর্ডার করুন (instant checkout) */
    .lux-order-now {
        border: 1.5px solid transparent;
        background: linear-gradient(135deg, #f85606 0%, #e0490a 100%);
        color: #fff;
        box-shadow: 0 6px 16px rgba(248, 86, 6, 0.28);
    }
    .lux-order-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(248, 86, 6, 0.42);
        color: #fff;
    }
    .lux-order-now:active { transform: translateY(0); }

    .lux-add-to-cart:disabled,
    .lux-order-now:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 420px) {
        .lux-cta-row { flex-direction: column; }
        .lux-add-to-cart, .lux-order-now { height: 44px; }
    }

    /* ── RESPONSIVE THUMB HEIGHT ── */
    @media (max-width: 900px)  { .lux-product-thumb { height: 360px; } }
    @media (max-width: 768px)  { .lux-product-thumb { height: 300px; } }
    @media (max-width: 540px)  { .lux-product-thumb { height: 260px; } }
    @media (max-width: 420px)  { .lux-product-thumb { height: 220px; } }
</style>
@endPushOnce

<div class="lux-product-card">
    <div class="lux-product-thumb">
        <a href="{{ url('product/' . $product->slug) }}">
            <img src="{{ $mainImage }}" alt="{{ $product->title }}" loading="lazy">
        </a>
    </div>

    <div class="lux-product-info">
        <div class="lux-info-top-row">
            <div style="min-width:0;flex:1;">
                <span class="lux-category-label">{{ $categoryName }}</span>
                <h2 class="lux-product-name">
                    <a href="{{ url('product/' . $product->slug) }}">{{ $product->title }}</a>
                </h2>
            </div>

            <button type="button" class="lux-wishlist-btn" aria-label="Save to Wishlist">
                Save to Wishlist ♡
            </button>
        </div>

        <div class="lux-variants-row">
            <div>
                <span class="lux-rating">{{ str_repeat('★', $fullStars) }}{{ str_repeat('☆', 5 - $fullStars) }}</span>
                <span class="lux-rating-count">
                    {{ $reviewCount > 0 ? $reviewCount . ' Review' . ($reviewCount > 1 ? 's' : '') : 'No reviews' }}
                </span>
            </div>

            @if ($colors->count() > 0)
                <div class="lux-color-variants">
                    @foreach ($colors->take($maxSwatches) as $color)
                        <span class="lux-color" style="background:{{ $color->code ?? '#ddd' }}"
                              title="{{ $color->name }}"></span>
                    @endforeach
                    @if ($colors->count() > $maxSwatches)
                        <span class="lux-rating-count">+{{ $colors->count() - $maxSwatches }}</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="lux-purchase-row">
            <p class="lux-product-price">৳ {{ number_format($price) }}</p>
        </div>

        <form id="lux-cart-form-{{ $product->id }}" action="{{ route('add.cart') }}" method="POST" class="lux-cta-form">
            @csrf
            <input type="hidden" name="id"  value="{{ $product->id }}">
            <input type="hidden" name="qty" value="1">
            <div class="lux-cta-row">
                <button type="button" class="lux-add-to-cart ajax-lux-cart-btn"
                        data-form-id="lux-cart-form-{{ $product->id }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Add to Cart
                </button>
                <button type="button" class="lux-order-now order-now-btn"
                        data-form-id="lux-cart-form-{{ $product->id }}">
                    অর্ডার করুন
                </button>
            </div>
        </form>
    </div>
</div>

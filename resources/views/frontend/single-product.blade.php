


{{-- Google Tag Manager + dataLayer helper (standalone page — not wrapped by the app layout) --}}
@include('partials.gtm-head')
@include('partials.tracking')
@include('partials.gtm-body')

<style>
.main-product-page-top-space{
    padding-top:110px;
}
@media(max-width:768px){
    .main-product-page-top-space{
        padding-top:85px;
    }
}
</style>

<div class='main-product-page-top-space'>
@include('layouts.frontend.partials.header_1')







<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Inter:wght@300;400;500;600&display=swap');

:root {
    --accent-color: #FFCC00;
    --deep-black: #000000;
    --soft-gray: #707070;
    --border-color: #e5e5e5;
    --bg-light: #fafafa;
    --ease: cubic-bezier(0.23, 1, 0.32, 1);
}

.boutique-wrapper {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
    gap: 80px;
    font-family: 'Inter', sans-serif;
    background: #fff;
}

.gallery-column {
    flex: 1.2;
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 350px;
}

.featured-stage {
    width: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 2px;
    background: #fafafa;
}

.featured-stage img {
    width: 100%;
    display: block;
    transition: transform 0.8s var(--ease);
}

.featured-stage:hover img {
    transform: scale(1.03);
}

.thumbnail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.thumbnail-grid img,
.thumbnail-grid video {
    width: 100%;
    height: 280px;
    object-fit: cover;
    cursor: pointer;
    opacity: 0.8;
    transition: var(--ease);
    border: 1px solid transparent;
    border-radius: 4px;
}

.video-item {
    grid-column: span 2;
    height: 350px !important;
    background: #000;
    border-radius: 8px;
    outline: none;
    opacity: 1 !important;
}

.thumbnail-grid img:hover,
.thumbnail-grid img.active {
    opacity: 1;
    border-color: var(--accent-color);
    transform: translateY(-5px);
}

.info-column {
    flex: 1;
    min-width: 350px;
    display: flex;
    flex-direction: column;
}

.category-label {
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--soft-gray);
    margin-bottom: 15px;
}

.brand-title {
    font-family: 'Cinzel Decorative', cursive;
    font-size: 42px;
    line-height: 1.2;
    color: var(--deep-black);
    margin: 0 0 20px 0;
    letter-spacing: -1px;
}

.rating-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
    font-size: 13px;
    color: var(--soft-gray);
}

.star-gold {
    color: var(--accent-color);
    font-size: 16px;
}

.price-tag {
    font-size: 28px;
    font-weight: 300;
    color: var(--deep-black);
    margin-bottom: 35px;
}

.price-tag del {
    color: #999;
    font-size: 20px;
    margin-right: 10px;
}

.specs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    border-top: 1px solid var(--border-color);
    padding-top: 30px;
    margin-bottom: 40px;
}

.spec-item label {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--soft-gray);
    margin-bottom: 5px;
}

.spec-item span {
    font-size: 15px;
    color: var(--deep-black);
    font-weight: 500;
}

.color-selection-container {
    margin: 30px 0;
    border-top: 1px solid var(--border-color);
    padding-top: 25px;
}

.selection-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--soft-gray);
    margin-bottom: 15px;
    display: block;
}

.swatch-group {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.swatch-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.color-swatch {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 1px solid #e0e0e0;
    position: relative;
    transition: var(--ease);
    background-clip: content-box;
    padding: 2px;
}

.swatch-wrapper.active .color-swatch {
    border-color: var(--deep-black);
    padding: 3px;
    transform: scale(1.1);
}

.swatch-name {
    font-size: 11px;
    color: var(--soft-gray);
    text-transform: capitalize;
    opacity: 0;
    transition: var(--ease);
}

.swatch-wrapper:hover .swatch-name,
.swatch-wrapper.active .swatch-name {
    opacity: 1;
}

.action-group {
    display: flex;
    gap: 15px;
    margin-bottom: 50px;
}

.btn-lux {
    flex: 1;
    padding: 20px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    transition: all 0.4s var(--ease);
    text-align: center;
}

.btn-dark {
    background: var(--deep-black);
    color: #fff;
}

.btn-gold {
    background: var(--accent-color);
    color: #000;
}

.btn-lux:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    opacity: 0.9;
}

.luxe-accordion {
    border-top: 1px solid var(--border-color);
}

details {
    border-bottom: 1px solid var(--border-color);
}

summary {
    list-style: none;
    padding: 25px 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

summary::-webkit-details-marker {
    display: none;
}

summary::after {
    content: '\002B';
    font-size: 18px;
    font-weight: 300;
}

details[open] summary::after {
    content: '\2212';
}

.details-content {
    padding-bottom: 30px;
    font-size: 15px;
    color: #555;
    line-height: 1.8;
}

@media (max-width: 900px) {
    .boutique-wrapper {
        gap: 40px;
        padding: 40px 20px;
        margin: 0 auto;
    }

    .gallery-column,
    .info-column {
        min-width: 100%;
    }

    .brand-title {
        font-size: 32px;
    }

    .thumbnail-grid img {
        height: 180px;
    }

    .action-group {
        flex-direction: column;
    }
}
.lux-search-form {
    display: none;
    align-items: center;
    gap: 8px;
}

.lux-search-form.active {
    display: flex;
}

.lux-search-form input {
    width: 180px;
    height: 36px;
    border: 1px solid #ddd;
    padding: 0 12px;
    font-size: 13px;
    outline: none;
}

.lux-search-form button {
    height: 36px;
    border: none;
    background: #000;
    color: #fff;
    padding: 0 12px;
    cursor: pointer;
}
</style>

@php
    $galleryImages = collect();

$productImages = is_array($product->image) ? $product->image : json_decode($product->image, true);

if (!is_array($productImages)) {
    $productImages = !empty($product->image) ? [$product->image] : [];
}

$mainImage = count($productImages) > 0
    ? asset('uploads/product/' . $productImages[0])
    : asset('frontend/images/placeholder.png');

foreach ($productImages as $img) {
    if (!empty($img)) {
        $galleryImages->push(asset('uploads/product/' . $img));
    }
}

    
    
    $galleryImages = $galleryImages->unique()->values();
    $productVideo = $product->video ?? $product->product_video ?? null;

if (!empty($productVideo)) {
    $productVideo = str_starts_with($productVideo, 'http')
        ? $productVideo
        : asset('uploads/product/video/' . $productVideo);
}

    $finalPrice = $product->discount_price ?: $product->regular_price;
    $reviewCount = $product->reviews ? $product->reviews->count() : 0;
    $firstCategory = optional($product->categories->first())->name ?? 'Premium Product';
@endphp

<div class="boutique-wrapper">
    <div class="gallery-column">
        <div class="featured-stage">
            <img id="featuredProductImage" src="{{ $mainImage }}" alt="{{ $product->title }}">
        </div>

        <div class="thumbnail-grid">
            @forelse($galleryImages as $key => $image)
                <img
                    src="{{ $image }}"
                    alt="{{ $product->title }} image {{ $key + 1 }}"
                    class="{{ $key === 0 ? 'active' : '' }}"
                    onclick="changeProductImage(this)"
                >
            @empty
                <img src="{{ $mainImage }}" alt="{{ $product->title }}" class="active" onclick="changeProductImage(this)">
            @endforelse
            @if(!empty($productVideo))
    <video class="video-item" controls muted loop playsinline>
        <source src="{{ $productVideo }}" type="video/mp4">
    </video>
@endif
        </div>
    </div>

    <div class="info-column">
        <span class="category-label">{{ $firstCategory }}</span>

        <h1 class="brand-title">{{ $product->title }}</h1>

        <div class="rating-box">
            <span class="star-gold">★★★★★</span>
            <span>({{ $reviewCount }} VERIFIED REVIEWS)</span>
        </div>

        <div class="price-tag">
            @if($product->discount_price)
                <del>{{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->regular_price }}</del>
                {{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->discount_price }}
            @else
                {{ setting('CURRENCY_CODE_MIN') ?? '৳' }} {{ $product->regular_price }}
            @endif
        </div>

        <div class="specs-grid">
            <div class="spec-item">
                <label>Product Code</label>
                <span>{{ $product->sku ?? $product->id }}</span>
            </div>
            <div class="spec-item">
                <label>Stock</label>
                <span>{{ ($product->quantity ?? 0) > 0 ? 'Available' : 'Out of Stock' }}</span>
            </div>
            <div class="spec-item">
                <label>Delivery</label>
                <span>Cash on Delivery</span>
            </div>
            <div class="spec-item">
                <label>Origin</label>
                <span>Bangladesh</span>
            </div>
        </div>

        @if(isset($colors_product) && $colors_product->count() > 0)
            <div class="color-selection-container">
                <span class="selection-label">Select Color</span>

                <div class="swatch-group">
                    @foreach($colors_product as $key => $color)
                        <div class="swatch-wrapper {{ $key === 0 ? 'active' : '' }}" data-color="{{ $color->slug ?? $color->name }}">
                            <div class="color-swatch" style="background: {{ $color->code ?? $color->color_code ?? '#ddd' }}"></div>
                            <span class="swatch-name">{{ $color->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form id="luxProductForm" action="{{ route('add.cart') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="qty" value="1">
            <input type="hidden" name="color" id="selectedColor" value="{{ isset($colors_product) && $colors_product->count() > 0 ? ($colors_product->first()->slug ?? $colors_product->first()->name) : 'blank' }}">
            <input type="hidden" name="size" value="blank">
            <input type="hidden" name="dynamic_prices" value="{{ $finalPrice }}">

            @if(isset($attributeGroups) && $attributeGroups->count() > 0)
                @foreach($attributeGroups as $attributeId => $values)
                    @php $attribute = optional($values->first())->attributes; @endphp
                    @if($attribute)
                        <input type="hidden" name="{{ $attribute->slug }}" value="{{ optional($values->first())->id ?? 'blank' }}">
                    @endif
                @endforeach
            @endif

            <div class="action-group">
                <button type="submit" class="btn-lux btn-dark" onclick="setAddToCartMode()">Add To Cart</button>
                <button type="button" class="btn-lux btn-gold" onclick="buyNowProduct()">Buy It Now</button>
            </div>
        </form>

        <div class="luxe-accordion">
            <details open>
                <summary>The Design Story</summary>
                <div class="details-content">
                    {!! $product->short_description ?? $product->full_description ?? 'Premium quality product with modern finishing and reliable performance.' !!}
                </div>
            </details>

            <details>
                <summary>Technical Details</summary>
                <div class="details-content">
                    {!! $product->full_description ?? $product->short_description ?? 'Product details will be updated soon.' !!}
                </div>
            </details>

            <details>
                <summary>Shipping & Concierge</summary>
                <div class="details-content">
                    Delivered via courier. Cash on Delivery available across Bangladesh.
                </div>
            </details>

            <details>
                <summary>Warranty & Returns</summary>
                <div class="details-content">
                    Warranty and return policy depends on product condition and seller policy.
                </div>
            </details>
        </div>
    </div>
</div>

<script>
function changeProductImage(el) {
    const featured = document.getElementById('featuredProductImage');
    featured.src = el.src;

    document.querySelectorAll('.thumbnail-grid img').forEach(img => img.classList.remove('active'));
    el.classList.add('active');
}

document.querySelectorAll('.swatch-wrapper').forEach(function (swatch) {
    swatch.addEventListener('click', function () {
        document.querySelectorAll('.swatch-wrapper').forEach(item => item.classList.remove('active'));
        this.classList.add('active');

        const selectedColor = document.getElementById('selectedColor');
        if (selectedColor) {
            selectedColor.value = this.dataset.color || 'blank';
        }
    });
});


function setAddToCartMode() {
    setTimeout(function () {
        window.location.href = "{{ route('cart') }}";
    }, 500);
}

function buyNowProduct() {
    const form = document.getElementById('luxProductForm');
    form.action = "{{ route('buy.product') }}";
    form.method = "GET";
    form.submit();
}
</script>

{{-- GA4 / Meta product_view (view_item) --}}
<script>
    (function () {
        if (!window.DL) return;
        window.DL.productView({
            currency: window.DL.currency,
            value: {{ (float) $finalPrice }},
            items: [{
                item_id: @json((string) $product->id),
                item_name: @json($product->title),
                price: {{ (float) $finalPrice }},
                quantity: 1
            }]
        });
    })();
</script>


</div>

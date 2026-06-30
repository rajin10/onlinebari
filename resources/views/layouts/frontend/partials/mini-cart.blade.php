{{-- Mini-cart dropdown body (loaded via /mini-cart) --}}
@php
    $cur = setting('CURRENCY_CODE_MIN') ?? '৳';
    $resolveImg = function ($img) {
        if (is_array($img)) {
            $img = $img[0] ?? null;
        } elseif (is_string($img) && str_starts_with(trim($img), '[')) {
            $decoded = json_decode($img, true);
            $img = is_array($decoded) ? ($decoded[0] ?? null) : $img;
        }
        return $img ? asset('uploads/product/' . $img) : asset('frontend/images/placeholder.png');
    };
@endphp

<div class="mini-cart__head">
    <span class="mini-cart__title">Your Cart</span>
    <span class="mini-cart__count">{{ $count }} {{ \Illuminate\Support\Str::plural('item', (int) $count) }}</span>
</div>

@if ($count > 0)
    <div class="mini-cart__items">
        @foreach ($carts as $cart)
            <div class="mini-cart__item">
                <a class="mini-cart__thumb" href="{{ url('product/' . ($cart->options->slug ?? '')) }}">
                    <img src="{{ $resolveImg($cart->options->image ?? null) }}" alt="{{ $cart->name }}" loading="lazy">
                </a>
                <div class="mini-cart__meta">
                    <a class="mini-cart__name" href="{{ url('product/' . ($cart->options->slug ?? '')) }}">{{ $cart->name }}</a>
                    <span class="mini-cart__qty">{{ $cart->qty }} × {{ number_format($cart->price) }} {{ $cur }}</span>
                </div>
                <div class="mini-cart__line">
                    <span class="mini-cart__subtotal">{{ number_format($cart->subtotal) }} {{ $cur }}</span>
                    <button type="button" class="mini-cart__remove" aria-label="Remove"
                        onclick="removeItem('{{ $cart->rowId }}', '{{ $cart->qty }}', '{{ $cart->id }}', '{{ addslashes($cart->name) }}', '{{ $cart->price }}'); if(window.refreshMiniCart) setTimeout(window.refreshMiniCart, 350);">&times;</button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mini-cart__foot">
        <div class="mini-cart__total">
            <span>Subtotal</span>
            <span>{{ number_format($total) }} {{ $cur }}</span>
        </div>
        <div class="mini-cart__actions">
            <a href="{{ route('cart') }}" class="mini-cart__btn mini-cart__btn--ghost">View Cart</a>
            <a href="{{ url('/checkout') }}" class="mini-cart__btn mini-cart__btn--solid">Checkout</a>
        </div>
    </div>
@else
    <div class="mini-cart__empty">
        <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <p>Your cart is empty</p>
        <a href="{{ route('home') }}" class="mini-cart__btn mini-cart__btn--solid">Start Shopping</a>
    </div>
@endif

{{--
    Landing CTA button.
    - When a product is configured (buyUrl set), every "order" button links
      straight to that product's checkout (GET /buy/product?id=…&qty=1).
    - Otherwise it falls back to a call-to-order tel: link so it is never dead.
    Expects: $label, and (from the parent view) $buyUrl + $phone.
--}}
@if (! empty($buyUrl))
    <a href="{{ $buyUrl }}" class="lp-cta">{{ $label }}</a>
@else
    <a href="tel:{{ $phone }}" class="lp-cta">{{ $label }}</a>
@endif

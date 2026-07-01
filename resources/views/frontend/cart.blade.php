@extends('layouts.frontend.app')
@use('App\Core\ShoppingCart\Facades\Cart')

@push('meta')
<meta name='description' content="Cart Products"/>
<meta name='keywords' content="@foreach(\App\Models\Tag::all() as $tag){{$tag->name.', '}}@endforeach" />
@endpush

@section('title', 'Cart Products')

@push('css')
<style>
:root {
    --premium-gold:#e9d180;
    --hover-gold:#ffcc00;
    --deep-gold:#b89c4a;
    --soft-bg:#fdfdfd;
    --mac-card:#ffffff;
    --text-main:#1a1a1a;
    --text-muted:#666;
    --border-color:#efefef;
}
.cart-page-wrap {
    background:var(--soft-bg);
    color:var(--text-main);
    padding:70px 20px;
    font-family:-apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", sans-serif;
}
.cart-container {
    max-width:1100px;
    margin:0 auto;
}
.cart-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    margin-bottom:40px;
}
.cart-header h1 {
    font-size:2rem;
    font-weight:700;
    margin:0;
    letter-spacing:-.5px;
}
.item-count {
    color:var(--text-muted);
    font-size:1rem;
}
.cart-grid {
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:50px;
}
.cart-items-list {
    border-top:1px solid var(--border-color);
}
.cart-item {
    display:flex;
    align-items:center;
    padding:25px 0;
    border-bottom:1px solid var(--border-color);
    transition:.3s ease;
}
.cart-item:hover {
    transform:translateY(-2px);
    background:#fff;
}
.product-img {
    width:100px;
    height:120px;
    background:#f5f5f5;
    border-radius:16px;
    object-fit:cover;
    margin-right:25px;
}
.product-info {
    flex:1;
}
.product-name {
    font-size:1.1rem;
    font-weight:600;
    margin-bottom:5px;
    display:block;
    color:#111;
}
.product-meta {
    color:var(--text-muted);
    font-size:.85rem;
}
.qty-pill {
    display:flex;
    align-items:center;
    background:#f1f1f1;
    border-radius:50px;
    padding:5px;
    width:fit-content;
    margin-top:15px;
}
.qty-btn {
    border:none;
    background:#fff;
    width:28px;
    height:28px;
    cursor:pointer;
    border-radius:50%;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:.2s;
    box-shadow:0 2px 5px rgba(0,0,0,.05);
}
.qty-btn:hover {
    background:var(--premium-gold);
}
.qty-val {
    padding:0 15px;
    font-weight:600;
    font-size:.9rem;
    border:0;
    background:transparent;
    width:48px;
    text-align:center;
}
.item-price-block {
    text-align:right;
}
.item-total {
    font-weight:700;
    font-size:1.1rem;
    margin-bottom:10px;
    display:block;
}
.remove-btn {
    background:none;
    border:none;
    color:#ff4d4d;
    font-size:.8rem;
    font-weight:500;
    cursor:pointer;
    padding:5px 10px;
    border-radius:6px;
    transition:.2s;
}
.remove-btn:hover {
    background:#fff5f5;
}
.summary-card {
    background:var(--mac-card);
    border-radius:28px;
    padding:40px;
    box-shadow:0 20px 60px rgba(0,0,0,.05);
    border:1px solid rgba(0,0,0,.03);
    height:fit-content;
    position:sticky;
    top:110px;
}
.summary-title {
    font-size:1.2rem;
    font-weight:700;
    margin-bottom:25px;
}
.calc-line {
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
    font-size:.95rem;
    color:var(--text-muted);
}
.grand-total-box {
    margin-top:25px;
    padding-top:25px;
    border-top:2px dashed #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.total-label {
    font-weight:700;
    font-size:1rem;
}
.total-amount {
    font-weight:800;
    font-size:1.6rem;
    color:var(--deep-gold);
}
.checkout-btn {
    display:block;
    width:100%;
    text-align:center;
    background:linear-gradient(135deg,#000,#222);
    color:#fff;
    border:none;
    padding:22px;
    border-radius:50px;
    font-weight:700;
    font-size:1rem;
    letter-spacing:1px;
    text-transform:uppercase;
    cursor:pointer;
    margin-top:30px;
    transition:all .4s ease;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}
.checkout-btn:hover {
    background:var(--hover-gold);
    color:#000;
    transform:translateY(-4px);
    box-shadow:0 15px 35px rgba(255,204,0,.3);
}
.checkout-btn.disabled {
    opacity:.45;
    pointer-events:none;
}
.promo-box {
    margin-top:30px;
    display:flex;
    gap:10px;
}
.promo-input {
    flex:1;
    padding:12px 15px;
    border:1px solid var(--border-color);
    border-radius:12px;
    background:#f9f9f9;
    font-size:.85rem;
}
.apply-btn {
    background:#f1f1f1;
    border:none;
    padding:0 20px;
    border-radius:12px;
    font-weight:600;
    font-size:.85rem;
    cursor:pointer;
}
.empty-cart-box {
    padding:35px 0;
    color:#999;
}
.disable {
    cursor:not-allowed !important;
    opacity:.5;
}
@media(max-width:900px) {
    .cart-page-wrap { padding:45px 15px; }
    .cart-grid { grid-template-columns:1fr; gap:30px; }
    .summary-card { position:static; padding:28px 22px; }
}
@media(max-width:560px) {
    .cart-header {
        display:block;
        margin-bottom:25px;
    }
    .item-count {
        display:block;
        margin-top:8px;
    }
    .cart-item {
        align-items:flex-start;
        gap:14px;
    }
    .product-img {
        width:82px;
        height:98px;
        margin-right:0;
        border-radius:12px;
    }
    .product-name {
        font-size:.95rem;
    }
    .item-price-block {
        min-width:74px;
    }
    .item-total {
        font-size:.9rem;
    }
}
</style>
@endpush

@section('content')
<div class="cart-page-wrap">
    <div class="cart-container">
        <div class="cart-header">
            <h1>Your Cart</h1>
            <span class="item-count" id="count_product">0 Items selected</span>
        </div>

        <div class="cart-grid">
            <div class="cart-items-list product-all"></div>

            <div class="summary-side">
                <div class="summary-card">
                    <div class="summary-title">Order Summary</div>

                    <div class="calc-line">
                        <span>Subtotal</span>
                        <span>৳ <span id="total-cart-amount">0.00</span></span>
                    </div>

                    <div class="calc-line">
                        <span>Estimated Shipping</span>
                        <span class="text-[#27ae60] font-semibold">Free</span>
                    </div>

                    <div class="calc-line">
                        <span>Tax (VAT)</span>
                        <span>৳ 0.00</span>
                    </div>

                    <div class="promo-box">
                        <input type="text" class="promo-input" placeholder="Coupon Code">
                        <button class="apply-btn" type="button">Apply</button>
                    </div>

                    <div class="grand-total-box">
                        <span class="total-label">Total</span>
                        <span class="total-amount">৳ <span id="grand-cart-total">0.00</span></span>
                    </div>

                    <a href="{{ route('checkout') }}" class="checkout-btn {{ Cart::count() > 0 ? '' : 'disabled' }}" id="checkoutBtn">
                        Proceed to Checkout
                    </a>

                    <p class="text-xs text-[#999] text-center mt-5">
                        Complimentary shipping on all luxury collections.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    $(document).on('click', '.value-plus', function(e) {
        let id = $(this).data('id');
        let btn = $(this);
        let divUpd = $(this).closest('.qty-pill').find('.value');
        let newVal = parseInt(divUpd.val(), 10) + 1;

        $.ajax({
            type:'GET',
            url:'/update/cart/' + id + '/' + newVal,
            dataType:'JSON',
            beforeSend:function(){ $(btn).addClass('disable'); },
            success:function(){ getCart(); },
            complete:function(){ $(btn).removeClass('disable'); }
        });
    });

    $(document).on('click', '.value-minus', function(e) {
        let divUpd = $(this).closest('.qty-pill').find('.value');
        let newVal = parseInt(divUpd.val(), 10) - 1;

        if (newVal >= 1) {
            let id = $(this).data('id');
            let btn = $(this);

            $.ajax({
                type:'GET',
                url:'/update/cart/' + id + '/' + newVal,
                dataType:'JSON',
                beforeSend:function(){ $(btn).addClass('disable'); },
                success:function(){ getCart(); },
                complete:function(){ $(btn).removeClass('disable'); }
            });
        }
    });

    $(document).on('click', '#remove-product', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let btn = $(this);

        $.ajax({
            type:'GET',
            url:'/destroy/cart/' + id,
            dataType:'JSON',
            beforeSend:function(){ $(btn).addClass('disable'); },
            success:function(){
                if (window.cart_items && window.cart_items[id]) {
                    let item = window.cart_items[id];
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        event:'remove_from_cart',
                        ecommerce:{
                            currency:'BDT',
                            value:parseFloat(item.price) * parseInt(item.qty),
                            items:[{
                                item_id:item.id,
                                item_name:item.name,
                                price:parseFloat(item.price),
                                quantity:parseInt(item.qty)
                            }]
                        }
                    });
                }
                getCart();
            },
            complete:function(){ $(btn).removeClass('disable'); }
        });
    });

    window.cart_items = {};

    function productImagePath(image) {
        if (!image) return '/frontend/images/placeholder.png';

        try {
            let parsed = JSON.parse(image);
            if (Array.isArray(parsed)) image = parsed[0];
        } catch(e) {}

        if (String(image).startsWith('http') || String(image).startsWith('/')) return image;

        return '/uploads/product/' + image;
    }

    function getCart() {
        $.ajax({
            type:'GET',
            url:"{!! route('get.cart') !!}",
            dataType:'JSON',
            success:function(response) {
                window.cart_items = response.carts;

                let total_qty = 0;
                let total = 0;
                let html = '';

                if (response.count > 0) {
                    $.each(response.carts, function(key, val) {
                        total_qty += parseInt(val.qty);
                        total += parseFloat(val.subtotal);

                        html += '<div class="cart-item">';
                        html += '<a href="/product/' + val.options.slug + '">';
                        html += '<img src="' + productImagePath(val.options.image) + '" alt="' + val.name + '" class="product-img">';
                        html += '</a>';

                        html += '<div class="product-info">';
                        html += '<a href="/product/' + val.options.slug + '" class="product-name">' + val.name + '</a>';
                        html += '<span class="product-meta">Seller: ' + (val.options.seller || '') + '</span>';
                        html += '<div class="qty-pill">';
                        html += '<button type="button" class="qty-btn value-minus" data-id="' + key + '">−</button>';
                        html += '<input type="text" class="qty-val value" value="' + val.qty + '" readonly>';
                        html += '<button type="button" class="qty-btn value-plus" data-id="' + key + '">+</button>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="item-price-block">';
                        html += '<span class="item-total">৳ ' + number_format(val.subtotal, 2, '.', ',') + '</span>';
                        html += '<button type="button" class="remove-btn" id="remove-product" data-id="' + key + '">Remove</button>';
                        html += '</div>';
                        html += '</div>';
                    });

                    $('#checkoutBtn').removeClass('disabled');
                } else {
                    html = '<div class="empty-cart-box">Your cart is empty</div>';
                    $('#checkoutBtn').addClass('disabled');
                }

                $('.product-all').html(html);
                $('#count_product').text(response.count + ' Items selected');
                $('#total-cart-amount').text(number_format(total, 2, '.', ','));
                $('#grand-cart-total').text(number_format(total, 2, '.', ','));
                $('span.qty').text(total_qty);

                if (response.count > 0) {
                    let ds_items = [];
                    $.each(response.carts, function(key, val) {
                        ds_items.push({
                            item_id:val.id,
                            item_name:val.name,
                            price:parseFloat(val.price),
                            quantity:parseInt(val.qty)
                        });
                    });

                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push({
                        event:'view_cart',
                        event_id:(window.DL ? window.DL.uuid() : undefined),
                        ecommerce:{
                            currency:'BDT',
                            value:parseFloat(total),
                            total_quantity:parseInt(total_qty),
                            items:ds_items
                        }
                    });
                }
            }
        });
    }

    getCart();

    function number_format(number, decimals, dec_point, thousands_sep) {
        let n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
            dec = typeof dec_point === 'undefined' ? '.' : dec_point,
            s = n.toFixed(prec).split('.');

        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        return s.join(dec);
    }
});
</script>
@endpush

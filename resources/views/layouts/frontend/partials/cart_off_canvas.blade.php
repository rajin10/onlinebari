<style>
    img {
        height: 28px;
        width: auto;
    }
</style>

<!-- Cart Trigger Button -->
<button class="custom-btn toggle-offcanvas bg-dark text-white" type="button" onclick="toggleOffcanvas()">
    <div class="" style="text-align: center; padding:10px;">
        <span class="" style="font-size: 24px;"><i class="fal fa-shopping-bag"></i></span> <br>
        {{ $count }} ITEMS
    </div>
    <div style="background: var(--primary_color); padding: 4px 10px;">
        {{ number_format(array_sum(array_column($carts->toArray(), 'subtotal'))) }} TK
    </div>
</button>

<!-- Offcanvas markup -->
<div class="custom-offcanvas show" id="offcanvasRight">
    <div class="offcanvas-header">
        <h5>Your Cart</h5>
        <button type="button" class="close" onclick="toggleOffcanvas()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <div class="offcanvas-body" style="height: auto; flex-grow: 1; overflow: auto;">
        <!-- Scrollable Cart Items Container -->
        <div class="cart-items-container" style="max-height: 56vh; overflow-y: unset;">
            <!-- Loop through $carts -->
            @foreach ($carts as $cart)
                <div class="cart-item d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                    <div class="d-flex align-items-start">
                        <!-- Vertical +/- Buttons -->
                        <div style="display: flex; flex-direction: column; align-items: center; margin-right: 10px;">
                            <button style="border: none; background: none; font-size: 1.2rem; cursor: pointer;" onclick="updateItem('{{ $cart->rowId }}', '{{ $cart->qty + 1 }}', '+')">+</button>
                            <span>{{ $cart->qty }}</span>
                            <button style="border: none; background: none; font-size: 1.2rem; cursor: pointer;" onclick="updateItem('{{ $cart->rowId }}', '{{ $cart->qty - 1 }}', '-')">-</button>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="item-details d-flex align-items-center">
                            <div class="product-image mr-3">
                                <img src="{{ asset('uploads/product/' . $cart->options->image) }}" alt="{{ $cart->name }}" style="width: 60px; height: auto;">
                            </div>
                            <div class="product-name font-weight-bold">
                                <small>
                                    {{ $cart->name }} <br> ({{ number_format($cart->price) }} TK x {{ $cart->qty }})
                                </small>
                            </div>
                        </div>
                    </div>
                
                    <!-- Total and Remove Button -->
                    <div class="total d-flex align-items-center font-weight-bold">
                        <small>
                            {{ number_format($cart->subtotal) }} TK
                        </small>
                        <b style="color: red; cursor: pointer; margin-left: 15px;" onclick="removeItem('{{ $cart->rowId }}', '{{ $cart->qty }}', '{{ $cart->id }}', '{{ addslashes($cart->name) }}', '{{ $cart->price }}')">X</b>
                    </div>
                </div>
            @endforeach
        </div>        
    </div>

    <!-- Dynamic Total Section -->
    <div class="cart-summary px-3 pb-3 mt-3">
        <div class="d-flex justify-content-between font-weight-bold mb-2 border-top pt-2">
            <span>Total</span>
            <span>{{ number_format(array_sum(array_column($carts->toArray(), 'subtotal'))) }} TK</span>
        </div>

        <!-- Checkout Button at the Bottom -->
        <a href="{{ url('/checkout') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
    </div>
</div>
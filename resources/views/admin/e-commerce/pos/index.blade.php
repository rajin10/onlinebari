@extends('layouts.admin.app')

@section('title', 'Modern POS Terminal')

@push('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.2);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --hover-shadow: 0 15px 45px rgba(102, 126, 234, 0.15);
            --accent: #667eea;
            --text-main: #2d3748;
            --text-muted: #718096;
            --bg-body: #f7fafc;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
        }

        .pos-container {
            display: flex;
            height: calc(100vh - 100px);
            min-height: 600px;
            margin: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
        }

        /* Responsive Container */
        @media (max-width: 991px) {
            .pos-container {
                flex-direction: column;
                height: auto;
                min-height: 0;
                overflow: visible;
                margin: 0;
                border-radius: 0;
            }
        }

        /* Left Panel - Products */
        .product-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
            min-width: 0;
            border-right: 1px solid #edf2f7;
        }

        .header-section {
            padding: 25px;
            background: #fff;
            border-bottom: 1px solid #edf2f7;
        }

        .header-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-title-row h3 {
            font-weight: 800;
            font-size: 24px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .search-wrapper {
            position: relative;
            max-width: 600px;
        }

        .search-input {
            width: 100%;
            padding: 18px 25px 18px 60px;
            border: 2px solid #edf2f7;
            border-radius: 20px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
        }

        .search-input:focus {
            background: #fff;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .search-icon-large {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 22px;
            color: var(--accent);
            opacity: 0.6;
        }

        .product-scroll-area {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            background: #fdfdfd;
        }

        .modern-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        /* Premium Product Card */
        .premium-card {
            background: #fff;
            border: 1px solid #edf2f7;
            border-radius: 20px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .premium-card::after {
            content: '\f067';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 20px;
            color: white;
            transition: all 0.4s;
            opacity: 0;
        }

        .premium-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--hover-shadow);
            border-color: var(--accent);
        }

        .premium-card:hover::after {
            top: -30px;
            right: -30px;
            opacity: 1;
        }

        .card-img-container {
            width: 100%;
            height: 140px;
            background: #f8fafc;
            border-radius: 15px;
            margin-bottom: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .premium-card:hover .card-img {
            transform: scale(1.1);
        }

        .card-title {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-main);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
            height: 42px;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .card-price {
            font-weight: 800;
            font-size: 18px;
            color: var(--accent);
        }

        .card-stock {
            font-size: 12px;
            color: var(--text-muted);
            background: #edf2f7;
            padding: 2px 8px;
            border-radius: 6px;
        }

        /* Right Panel - Cart */
        .cart-panel {
            width: 450px;
            display: flex;
            flex-direction: column;
            background: #fff;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        @media (max-width: 991px) {
            .cart-panel {
                width: 100%;
                border-left: none;
                border-top: 1px solid #edf2f7;
            }
        }

        .cart-header-modern {
            padding: 25px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header-modern h4 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .cart-items-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fbfcfd;
        }

        /* Modern Cart Item */
        .modern-cart-item {
            display: flex;
            gap: 15px;
            background: white;
            padding: 15px;
            border-radius: 20px;
            margin-bottom: 15px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s;
            animation: slideInUp 0.4s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-cart-item:hover {
            box-shadow: var(--card-shadow);
            border-color: #e2e8f0;
        }

        .cart-item-thumb {
            width: 65px;
            height: 65px;
            border-radius: 12px;
            object-fit: cover;
            background: #f8fafc;
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 4px;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-item-price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-price-tag {
            font-weight: 800;
            color: var(--accent);
            font-size: 16px;
        }

        .cart-item-ctrl {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            padding: 4px 12px;
            border-radius: 12px;
        }

        .ctrl-btn {
            border: none;
            background: transparent;
            color: var(--text-main);
            font-weight: 900;
            cursor: pointer;
            padding: 5px;
            opacity: 0.7;
            transition: 0.2s;
        }

        .ctrl-btn:hover {
            opacity: 1;
            color: var(--accent);
        }

        .item-qty {
            font-weight: 800;
            font-size: 14px;
            min-width: 25px;
            text-align: center;
        }

        .trash-action {
            color: #ef4444;
            background: #fef2f2;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .trash-action:hover {
            background: #ef4444;
            color: white;
        }

        /* Cart Footer Area */
        .cart-footer {
            padding: 25px;
            background: #fff;
            border-top: 2px solid #f1f5f9;
        }

        .summary-block {
            margin-bottom: 20px;
        }

        .flex-row-between {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 15px;
            color: var(--text-muted);
        }

        .grand-total-row {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px dashed #edf2f7;
            font-size: 22px;
            font-weight: 900;
            color: var(--text-main);
        }

        .grand-total-val {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .action-button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .modern-checkout-btn {
            width: 100%;
            padding: 18px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .modern-checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
        }

        .modern-checkout-btn:disabled {
            background: #cbd5e0;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Form Overlay */
        #customerFormModern {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 100;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 25px;
            overflow-y: auto;
        }

        #customerFormModern.open {
            top: 0;
        }

        .close-form-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background: #f1f5f9;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }

        /* Empty State */
        .empty-state-modern {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            height: 100%;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state-modern i {
            font-size: 80px;
            margin-bottom: 25px;
            opacity: 0.15;
            background: var(--secondary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .empty-state-modern p {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .empty-state-modern span {
            font-size: 14px;
            opacity: 0.7;
        }

        .loading-fullscreen {
            grid-column: 1 / -1;
            text-align: center;
            padding: 100px 0;
        }

        .spinner-modern {
            width: 50px;
            height: 50px;
            border: 5px solid #edf2f7;
            border-top: 5px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')

    <div class="pos-container">
        <!-- Dashboard Exit -->
        <div class="absolute left-5 top-[-50px] flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-full border border-[#007bff] px-4 py-2 text-sm font-medium text-[#007bff] transition-colors hover:bg-[#007bff] hover:text-white">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <h4 class="m-0 text-lg font-extrabold text-slate-600">POS Terminal</h4>
        </div>

        <!-- Left Panel: Product Discovery -->
        <div class="product-panel">
            <div class="header-section">
                <div class="header-title-row">
                    <h3>Explore Products</h3>
                    <div class="inline-block rounded-[12px] px-[15px] py-[10px] text-[75%] font-bold leading-none">
                        <i class="fas fa-clock"></i> <span id="realtimeClock">--:-- --</span>
                    </div>
                </div>
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon-large"></i>
                    <input type="text" class="search-input" id="searchProduct"
                        placeholder="Search by name, category or SKU...">
                </div>
            </div>

            <div class="product-scroll-area">
                <div class="modern-grid" id="productGrid">
                    <div class="empty-state-modern">
                        <i class="fas fa-search"></i>
                        <p>Search to begin</p>
                        <span>Start typing to find products in your inventory</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Smart Cart -->
        <div class="cart-panel">
            <div class="cart-header-modern">
                <h4><i class="fas fa-shopping-bag"></i> Checkout Cart</h4>
                <div class="cart-badge"><span id="cartCount">0</span> Items</div>
            </div>

            <div class="cart-items-scroll" id="cartItems">
                <div class="empty-state-modern">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Bag is empty</p>
                    <span>Your selected items will appear here</span>
                </div>
            </div>

            <!-- Cart Summary & Actions -->
            <div class="cart-footer" id="cartFooterModern" style="display: none;">
                <div class="summary-block">
                    <div class="flex-row-between">
                        <span>Subtotal</span>
                        <span id="subtotal">৳0.00</span>
                    </div>
                    <div class="flex-row-between">
                        <span>Delivery Fee</span>
                        <span id="shipping">৳0.00</span>
                    </div>
                    <div class="flex-row-between grand-total-row">
                        <span>Order Total</span>
                        <span class="grand-total-val" id="total">৳0.00</span>
                    </div>
                </div>

                <div class="action-button-group">
                    <button class="modern-checkout-btn" id="openFormBtn">
                        Review & Complete Order <i class="fas fa-arrow-right"></i>
                    </button>
                    <button id="clearCartBtn"
                        class="w-full cursor-pointer border-none bg-transparent py-1 text-sm font-bold text-slate-500 transition-colors hover:text-slate-700">
                        Clear All Items
                    </button>
                </div>
            </div>

            <!-- Modern Form Drawer -->
            <div id="customerFormModern">
                <div class="close-form-btn" id="closeFormBtn"><i class="fas fa-times"></i></div>
                <h4 class="mb-4 text-xl font-extrabold text-slate-700">Customer Details</h4>
                <div id="alertContainer"></div>

                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700">First Name</label>
                        <input type="text" id="firstName" placeholder="Enter first name" required
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700">Last Name</label>
                        <input type="text" id="lastName" placeholder="Optional"
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700">Phone Number</label>
                    <input type="tel" id="phone" placeholder="Contact number" required
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700">Address</label>
                    <textarea id="address" rows="3" placeholder="Full delivery address" required
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                </div>

                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700">City</label>
                        <select id="city" required
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">Select City</option>
                            <option value="Dhaka">Dhaka</option>
                            <option value="Chittagong">Chittagong</option>
                            <option value="Sylhet">Sylhet</option>
                            <option value="Rajshahi">Rajshahi</option>
                            <option value="Khulna">Khulna</option>
                            <option value="Barishal">Barishal</option>
                            <option value="Rangpur">Rangpur</option>
                            <option value="Mymensingh">Mymensingh</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700">District</label>
                        <input type="text" id="district" placeholder="Area/District"
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700">Payment Method</label>
                    <select id="paymentMethod" required
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="cash">Cash on Delivery</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                    </select>
                </div>

                <div class="mb-4" id="transactionFields" style="display: none;">
                    <label class="block text-sm font-medium text-slate-700">Transaction ID</label>
                    <input type="text" id="transactionId" placeholder="TrxID if applicable"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                <button class="modern-checkout-btn mt-4" id="checkoutBtn">
                    Place Order Now <i class="fas fa-check-double"></i>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cart = [];
        let products = [];

        const shippingCharge = 60;
        const shippingOutside = 120;
        const freeShippingAbove = 1000;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Dynamic Clock
        function updateClock() {
            const now = new Date();
            let h = now.getHours();
            let m = now.getMinutes();
            let ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12; // the hour '0' should be '12'
            m = m < 10 ? '0' + m : m;
            const timeStr = h + ':' + m + ' ' + ampm;
            $('#realtimeClock').text(timeStr);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Form Drawer Logic
        $('#openFormBtn').on('click', () => {
            if (!cart.length) {
                showAlert('Your bag is empty! Add some products first.', 'warning');
                return;
            }
            $('#customerFormModern').addClass('open');
        });
        $('#closeFormBtn, .pos-container .product-panel').on('click', (e) => {
            if (!$(e.target).closest('#customerFormModern').length || $(e.target).is(
                    '#closeFormBtn, #closeFormBtn i')) {
                $('#customerFormModern').removeClass('open');
            }
        });

        /* ===============================
            SEARCH & LOAD
        =============================== */
        function loadProducts(query = '') {
            if (query.trim().length === 0) {
                $('#productGrid').html(`
                <div class="empty-state-modern">
                    <i class="fas fa-search"></i>
                    <p>Search to begin</p>
                    <span>Start typing to find products in your inventory</span>
                </div>
            `);
                products = [];
                return;
            }

            $('#productGrid').html(`
            <div class="loading-fullscreen">
                 <div class="spinner-modern"></div>
                 <p class="text-muted">Searching Inventory...</p>
            </div>
        `);

            $.ajax({
                url: '/admin/pos/search-products',
                method: 'GET',
                data: {
                    q: query
                },
                success(res) {
                    products = res;
                    renderProducts(res);
                },
                error() {
                    $('#productGrid').html(
                        '<div class="loading-fullscreen text-danger">Inventory search failed!</div>');
                }
            });
        }

        function renderProducts(items) {
            const grid = $('#productGrid');
            grid.empty();

            if (!items.length) {
                grid.html(`
                <div class="empty-state-modern">
                    <i class="fas fa-search"></i>
                    <p>No matches found</p>
                    <span>Try searching with different keywords</span>
                </div>
            `);
                return;
            }

            items.forEach(p => {
                const price = p.discount_price ?? p.regular_price ?? p.price;
                const img = p.image ? `/uploads/product/${p.image}` : '/uploads/product/default.png';

                grid.append(`
                <div class="premium-card" onclick="addProductToCart(${p.id})">
                    <div class="card-img-container">
                        <img src="${img}" class="card-img" onerror="this.src='/uploads/product/default.png'">
                    </div>
                    <div class="card-title" title="${p.title}">${p.title}</div>
                    <div class="card-meta">
                        <div class="card-price">৳${price}</div>
                        <div class="card-stock">Stock: ${p.quantity}</div>
                    </div>
                </div>
            `);
            });
        }

        let searchTimer;
        $('#searchProduct').on('input', function() {
            clearTimeout(searchTimer);
            const q = $(this).val();
            searchTimer = setTimeout(() => loadProducts(q), 400);
        });

        /* ===============================
            CART LOGIC
        =============================== */
        function addProductToCart(id) {
            const product = products.find(p => p.id === id);
            if (!product || product.quantity <= 0) {
                if (product && product.quantity <= 0) showAlert('Product is out of stock!', 'danger');
                return;
            }

            const exists = cart.find(i => i.product_id === product.id);
            if (exists) {
                if (exists.qty < exists.max_stock) {
                    exists.qty++;
                } else {
                    showAlert('Max stock reached in cart!', 'warning');
                    return;
                }
            } else {
                cart.unshift({
                    product_id: product.id,
                    name: product.title,
                    price: parseFloat(product.discount_price ?? product.regular_price ?? product.price),
                    image: product.image ? `/uploads/product/${product.image}` : '/uploads/product/default.png',
                    max_stock: product.quantity,
                    qty: 1
                });
                $('#cartItems').animate({
                    scrollTop: 0
                }, 'fast');
            }
            updateCart();
        }

        function updateCart() {
            const box = $('#cartItems');
            $('#cartCount').text(cart.length);

            if (!cart.length) {
                box.html(`
                <div class="empty-state-modern">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Bag is empty</p>
                    <span>Your selected items will appear here</span>
                </div>
            `);
                $('#cartFooterModern').hide();
                return;
            }

            $('#cartFooterModern').show();
            box.empty();

            cart.forEach((item, i) => {
                box.append(`
                <div class="modern-cart-item">
                    <img src="${item.image}" class="cart-item-thumb">
                    <div class="cart-item-info">
                        <div class="cart-item-title" title="${item.name}">${item.name}</div>
                        <div class="cart-item-price-row">
                            <span class="item-price-tag">৳${item.price}</span>
                            <div class="cart-item-ctrl">
                                <button class="ctrl-btn" onclick="qtyAdjust(${i}, -1)">-</button>
                                <span class="item-qty">${item.qty}</span>
                                <button class="ctrl-btn" onclick="qtyAdjust(${i}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="trash-action" onclick="removeItem(${i})">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            `);
            });

            updateSummary();
        }

        function qtyAdjust(i, delta) {
            const item = cart[i];
            if (delta > 0 && item.qty < item.max_stock) {
                item.qty++;
            } else if (delta < 0 && item.qty > 1) {
                item.qty--;
            } else if (delta > 0) {
                showAlert('Stock limit reached!', 'warning');
            }
            updateCart();
        }

        function removeItem(i) {
            cart.splice(i, 1);
            updateCart();
        }

        $('#clearCartBtn').on('click', () => {
            cart = [];
            updateCart();
        });

        function updateSummary() {
            const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
            const city = $('#city').val();
            let shipping = subtotal >= freeShippingAbove ? 0 : (city === 'Dhaka' ? shippingCharge : shippingOutside);

            $('#subtotal').text('৳' + subtotal.toFixed(2));
            $('#shipping').text('৳' + shipping.toFixed(2));
            $('#total').text('৳' + (subtotal + shipping).toFixed(2));
        }

        $('#city').on('change', updateSummary);

        /* ===============================
            ORDER SUBMISSION
        =============================== */
        $('#checkoutBtn').on('click', function() {
            const btn = $(this);
            if (!cart.length) return;

            // Simple validation
            if (!$('#firstName').val() || !$('#phone').val() || !$('#address').val()) {
                showAlert('Please fill required fields!', 'danger');
                return;
            }

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            const data = {
                first_name: $('#firstName').val(),
                last_name: $('#lastName').val(),
                phone: $('#phone').val(),
                email: $('#email').val(),
                address: $('#address').val(),
                city: $('#city').val(),
                district: $('#district').val(),
                payment_method: $('#paymentMethod').val(),
                transaction_id: $('#transactionId').val(),
                cart_items: cart
            };

            $.post('/admin/pos/store-order', data, res => {
                showAlert('Success! Order #' + res.invoice + ' completed.', 'success');
                cart = [];
                updateCart();
                $('#customerFormModern').removeClass('open');
                // Trigger print or receipt here if needed
            }).fail(err => {
                let msg = 'Order failed to save!';
                if (err.responseJSON) {
                    if (err.responseJSON.error) msg = err.responseJSON.error;
                    else if (err.responseJSON.message) msg = err.responseJSON.message;

                    if (err.responseJSON.errors) {
                        const firstErr = Object.values(err.responseJSON.errors)[0][0];
                        msg = firstErr;
                    }
                }
                showAlert(msg, 'danger');
            }).always(() => {
                btn.prop('disabled', false).html('Place Order Now <i class="fas fa-check-double"></i>');
            });
        });

        function showAlert(msg, type) {
            $('#alertContainer').html(
                `<div class="alert alert-${type} shadow-sm border-0" style="border-radius: 12px; font-weight: 600;">${msg}</div>`
                );
            setTimeout(() => $('.alert').fadeOut(), 6000);
        }

        // Payment method switch
        $('#paymentMethod').on('change', function() {
            if ($(this).val() !== 'cash') $('#transactionFields').slideDown();
            else $('#transactionFields').slideUp();
        });
    </script>
@endpush

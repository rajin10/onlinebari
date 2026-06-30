<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.frontend.partials.meta')
    @include('layouts.global')
    @include('layouts.frontend.partials.style')
    {!! setting('fb_pixel') !!}
    {{-- <!-- Custom Head Code --> --}}
    {!! setting('header_code') !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    
    
        /* ===== Global layout adjustments ===== */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Ensure header/footer can span full width */
        .site-header, .site-footer {
            width: 100%;
        }

        /* Inner content wrapper — where your page content lives (centered, max-width) */
        .site-inner {
            max-width: 100%;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            padding-left: 20px;   /* preserves your previous spacing */
            padding-right: 20px;
        }

        /* Make sure header/footer's inner .container is constrained to the same max width */
        .site-header .container,
        .site-footer .container,
        /* If your header partial uses .header-row container, ensure it also respects max width */
        .main-header .header-row.container,
        .top-info .container {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Prevent accidental global override like earlier .container { width:100% !important } */
        /* If some other inline styles try to force .container to full width with !important, the best way
           is to scope the forced full-width to header/footer only. Here we make .container default behave normally. */
        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding-left: 20px;
            padding-right: 20px;
            box-sizing: border-box;
        }

        /* Offcanvas / custom styles (kept from your file, but scoped) */
        .products .product .thumbnail img:hover {
            transform: scale(1.5);
            transition: 0.5s ease-out;
        }

        .offcanvas-body {
            padding-bottom: 60px; /* Space for checkout button */
            height: 93%;
        }

        .cart-items-container {
            max-height: 60vh; /* Adjust based on screen height */
            overflow-y: auto;
        }

        .btn-primary {
            bottom: 10px;
        }

        .custom-btn {
            position: fixed;
            top: 50%;
            bottom: auto;
            right: 0;
            transform: translateY(-50%);
            z-index: 1000;
            font-size: 12px;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            width: auto;
            border: none;
            overflow: hidden;
        }

        .custom-offcanvas {
            margin-top: 6.6%;
            position: fixed;
            top: 0;
            right: -100vw;
            width: 350px;
            height: 86%;
            background-color: #fff;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            transition: right 0.3s ease;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }

        .custom-offcanvas.show {
            right: 0;
        }

        @media only screen and (max-width: 600px) {
            .custom-offcanvas {
                width: 100%;
                height: 86%;
                right: -100vw;
                margin-top: 14%;
            }
            .site-inner{
                padding:0;
            }
        }

        .offcanvas-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .offcanvas-body {
            padding: 1rem;
        }

        /* Keep your .containe and .copy spacing, but don't let them control global container width */
        .containe, .copy {
            padding: 0px 20px !important;
            box-sizing: border-box;
        }

        /* Ensure top-info and main-header visuals remain full width but inner content constrained */
        .top-info {
            width: 100%;
            box-sizing: border-box;
        }

        .main-header {
            width: 100%;
            box-sizing: border-box;
        }

        /* If you use .app-header wrapper, give it full width and ensure sticky behavior can work */
        .app-header {
            width: 100%;
            box-sizing: border-box;
        }
        
        .site-footer > footer {
    display: none !important;
}
    </style>
  


</head>

<body>
    <div id="cart-container" style="display: none"></div>

    {{-- Remove previous in-body .container override; we have scoped CSS above --}}

    <!-- Keep Facebook / body codes -->
    @php echo setting('body_code');@endphp

    {{-- Facebook SDK --}}
    @if (env('FACEBOOK_SKD_ON') == 1)
        <div id="fb-root"></div>
        <div id="fb-customer-chat" class="fb-customerchat"></div>
        <script>
            var chatbox = document.getElementById('fb-customer-chat');
            chatbox.setAttribute("page_id", "523283677850901");
            chatbox.setAttribute("attribution", "biz_inbox");
        </script>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    xfbml: true,
                    version: 'v13.0'
                });
            };
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <style>
            .fb_dialog_content iframe {
                bottom: 105px !important;
            }
            
        </style>
    @endif

   {{-- Site header (full width) --}}
    <header class="site-header app-header">
        @if (!empty(setting('TOP_HEADER_STYLE')))
            @include('layouts.frontend.partials.header_' . setting('TOP_HEADER_STYLE'))
        @else
            @include('layouts.frontend.partials.header_1')
        @endif
    </header>
    
    {{-- Main site inner content (centered, max-width: 1400px) --}}
    <main class="site-inner" role="main" id="main-content">
        @yield('content')
    </main>

    {{-- Site footer (full width) --}}
   <footer class="site-footer">
    @include('layouts.frontend.partials.footer')
</footer>

    <!-- JavaScript for toggle functionality (offcanvas) -->
    <script>
        function toggleOffcanvas() {
            const offcanvas = document.getElementById('offcanvasRight')
            if (!offcanvas) return;
            offcanvas.classList.toggle('show')
        }
    </script>

    <script>
        function loadCartOnCanvas(shouldToggle = true) {
            fetch('/get-off-canvas-cart')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-container').innerHTML = data.html
                    if (shouldToggle) toggleOffcanvas()
                })
                .catch(error => console.error('Error updating cart:', error))
        }
        window.addEventListener('load', loadCartOnCanvas);

        function removeItem(rowId, qnty, itemId, itemName, itemPrice) {
            fetch('/destroy/cart/' + rowId)
                .then(response => response.json())
                .then(data => {
                    // GA4 remove_from_cart event
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        "event": "remove_from_cart",
                        "ecommerce": {
                            "currency": "BDT",
                            "value": parseFloat(itemPrice) * parseInt(qnty),
                            "items": [
                                {
                                    "item_id": itemId,
                                    "item_name": itemName,
                                    "price": parseFloat(itemPrice),
                                    "quantity": parseInt(qnty)
                                }
                            ]
                        }
                    });

                    var el1 = document.getElementById('total-cart-amount');
                    var el2 = document.getElementById('total-cart-amount2');
                    if (el1) el1.textContent = Number(el1.textContent || 0) - qnty;
                    if (el2) el2.textContent = Number(el2.textContent || 0) - qnty;

                    loadCartOnCanvas(false);

                    if (window.jQuery && $.toast) {
                        $.toast({
                            heading: 'Congratulations',
                            text: 'Product removed successfully.',
                            icon: 'success',
                            position: 'top-right',
                            stack: false
                        });
                    }
                })
                .catch(error => console.error('Error updating cart:', error))
        }

        function updateItem(rowId, qnty, operation) {
            fetch('/update/cart/' + rowId + '/' + qnty)
                .then(response => response.json())
                .then(data => {

                    var el1 = document.getElementById('total-cart-amount');
                    var el2 = document.getElementById('total-cart-amount2');
                    if (el1) {
                        if (operation == '+') el1.textContent = Number(el1.textContent || 0) + 1;
                        else el1.textContent = Number(el1.textContent || 0) - 1;
                    }
                    if (el2) {
                        if (operation == '+') el2.textContent = Number(el2.textContent || 0) + 1;
                        else el2.textContent = Number(el2.textContent || 0) - 1;
                    }

                    loadCartOnCanvas(false);

                    if (window.jQuery && $.toast) {
                        $.toast({
                            heading: 'Congratulations',
                            text: 'Cart updated successfully.',
                            icon: 'success',
                            position: 'top-right',
                            stack: false
                        });
                    }
                })
                .catch(error => console.error('Error updating cart:', error))
        }
    </script>

    <!-- OVERRIDE CSS _@stack('override_css') -->
    <style>
        @stack('override_css')
        {{ setting('override_css') }}
    </style>

    <x-notify::notify />
    @include('layouts.frontend.partials.script')
    @include('layouts.frontend.partials.mobile-sidebar')

<script>
(function () {
    const heartbeatUrl = "{{ route('visitor.heartbeat') }}";
    const leaveUrl = "{{ route('visitor.leave') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function sendHeartbeat() {
        fetch(heartbeatUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                url: window.location.href
            }),
            keepalive: true
        }).catch(function () {});
    }

    function sendLeave() {
        const data = new FormData();
        data.append('_token', csrfToken);

        navigator.sendBeacon(leaveUrl, data);
    }

    sendHeartbeat();
    setInterval(sendHeartbeat, 5000);

    window.addEventListener('pagehide', sendLeave);
})();
</script>

</body>

</html>


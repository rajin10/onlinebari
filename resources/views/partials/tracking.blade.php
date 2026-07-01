{{-- Reusable dataLayer helper — defines window.DL and auto-fires page_view.
     Include once, right after the GTM head snippet, on every page (storefront + landing).
     Safe to call window.DL.* from any inline script or @push('js') block. --}}
@php
    $__routeName = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
    $__pageTypeMap = [
        'home'              => 'home',
        'product.details'   => 'product',
        'product.cam.details' => 'product',
        'cart'              => 'cart',
        'checkout'          => 'checkout',
        'order.success'     => 'purchase',
        'category.product'  => 'category',
        'subCategory.product' => 'category',
        'collection.product' => 'category',
        'landing.lice-comb' => 'landing',
        'landing.rust-removals' => 'landing',
    ];
    $__pageType = $__pageTypeMap[$__routeName] ?? 'other';
@endphp
<script>
    (function () {
        if (window.DL) return; /* define once */
        window.dataLayer = window.dataLayer || [];

        function uuid() {
            if (window.crypto && crypto.randomUUID) { try { return crypto.randomUUID(); } catch (e) {} }
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = (Math.random() * 16) | 0, v = c === 'x' ? r : (r & 0x3) | 0x8;
                return v.toString(16);
            });
        }

        /**
         * Core push. Always attaches a unique event_id (for Meta CAPI / sGTM
         * deduplication) unless one is supplied. Clears the previous `ecommerce`
         * object before ecommerce events (GA4 best practice).
         */
        function push(event, ecommerce, extra) {
            extra = extra || {};
            var payload = { event: event, event_id: extra.event_id || uuid() };
            for (var k in extra) { if (k !== 'event_id') payload[k] = extra[k]; }
            if (ecommerce) {
                window.dataLayer.push({ ecommerce: null });
                payload.ecommerce = ecommerce;
            }
            window.dataLayer.push(payload);
            return payload.event_id;
        }

        window.DL = {
            uuid: uuid,
            push: push,
            currency: @json(config('tracking.currency', 'BDT')),
            pageView:      function (extra)     { return push('page_view', null, extra); },
            productView:   function (ec, extra) { return push('product_view', ec, extra); },
            addToCart:     function (ec, extra) { return push('add_to_cart', ec, extra); },
            viewCart:      function (ec, extra) { return push('view_cart', ec, extra); },
            beginCheckout: function (ec, extra) { return push('begin_checkout', ec, extra); },
            purchase:      function (ec, extra) { return push('purchase', ec, extra); }
        };

        /* ---- Auto page_view (every page) ---- */
        function firePageView() {
            window.DL.pageView({
                page_type: @json($__pageType),
                page_path: window.location.pathname,
                page_location: window.location.href,
                page_title: document.title
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', firePageView);
        } else {
            firePageView();
        }
    })();
</script>

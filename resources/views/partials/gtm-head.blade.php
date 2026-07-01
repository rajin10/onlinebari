{{-- Google Tag Manager (head) — single source of truth, include as the FIRST tag in <head>. --}}
@php $gtmId = config('tracking.gtm_id'); @endphp
@if (!empty($gtmId))
    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            if (w.__gtmLoaded) return; /* prevent duplicate container load */
            w.__gtmLoaded = true;
            w[l] = w[l] || [];
            w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', @json($gtmId));
    </script>
    <!-- End Google Tag Manager -->
@endif

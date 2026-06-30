{{-- ============================================================
     Floating WhatsApp button (backend-controlled)
     Settings: Admin → Marketing → Announcement Bar
============================================================ --}}
@php
    $waEnabled = setting('WHATSAPP_FLOAT_STATUS', '1') == '1';
@endphp

@if ($waEnabled)
    @php
        $raw    = setting('WHATSAPP_FLOAT_NUMBER', setting('whatsapp', '01624109210'));
        $digits = preg_replace('/\D/', '', (string) $raw);

        // Normalise to international (Bangladesh-friendly) for wa.me
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $intl = '880' . substr($digits, 1);
        } elseif (strlen($digits) === 10) {
            $intl = '880' . $digits;
        } else {
            $intl = $digits;
        }

        $message  = setting('WHATSAPP_FLOAT_MESSAGE', 'আমি একটি প্রোডাক্ট অর্ডার করতে চাই।');
        $tooltip  = setting('WHATSAPP_FLOAT_TOOLTIP', 'Chat with us on WhatsApp');
        $delay    = (int) setting('WHATSAPP_FLOAT_DELAY', '3');
        $showBadge = setting('WHATSAPP_FLOAT_BADGE', '1') == '1';
        $waLink = 'https://wa.me/' . $intl . ($message ? '?text=' . rawurlencode($message) : '');
    @endphp

    @if ($intl)
        <a href="{{ $waLink }}" target="_blank" rel="noopener"
            class="wa-float" id="waFloat" aria-label="{{ $tooltip }}"
            data-delay="{{ max(0, $delay) }}">
            <span class="wa-float__ring" aria-hidden="true"></span>
            <span class="wa-float__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor">
                    <path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.207zm5.439-6.32c-.075-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
            </span>
            @if ($showBadge)
                <span class="wa-float__badge" aria-hidden="true">1</span>
            @endif
            <span class="wa-float__tip">{{ $tooltip }}</span>
        </a>

        <style>
            .wa-float {
                position: fixed;
                right: 22px;
                bottom: 24px;
                width: 58px;
                height: 58px;
                border-radius: 50%;
                background: linear-gradient(145deg, #29d366 0%, #1faf52 100%);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 26px rgba(18, 140, 70, 0.42), 0 3px 8px rgba(0, 0, 0, 0.18);
                z-index: 13000;
                text-decoration: none;
                opacity: 0;
                transform: translateY(16px) scale(0.85);
                pointer-events: none;
                transition: opacity 0.45s ease, transform 0.45s cubic-bezier(0.18, 0.89, 0.32, 1.28),
                    box-shadow 0.3s ease;
            }

            .wa-float.is-visible {
                opacity: 1;
                transform: translateY(0) scale(1);
                pointer-events: auto;
            }

            .wa-float:hover {
                transform: translateY(-2px) scale(1.06);
                box-shadow: 0 14px 32px rgba(18, 140, 70, 0.5), 0 4px 10px rgba(0, 0, 0, 0.2);
                color: #fff;
            }

            .wa-float__icon {
                position: relative;
                z-index: 2;
                display: flex;
                line-height: 0;
            }

            /* Soft expanding ring — subtle, not distracting */
            .wa-float__ring {
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: rgba(41, 211, 102, 0.45);
                z-index: 1;
                animation: wa-pulse 2.6s ease-out infinite;
            }

            @keyframes wa-pulse {
                0% { transform: scale(1); opacity: 0.6; }
                70% { transform: scale(1.7); opacity: 0; }
                100% { transform: scale(1.7); opacity: 0; }
            }

            .wa-float__badge {
                position: absolute;
                top: -3px;
                right: -3px;
                min-width: 19px;
                height: 19px;
                padding: 0 5px;
                border-radius: 999px;
                background: #ff3b30;
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                line-height: 19px;
                text-align: center;
                box-shadow: 0 0 0 2px #fff;
                z-index: 3;
            }

            .wa-float__tip {
                position: absolute;
                right: 70px;
                top: 50%;
                transform: translateY(-50%) translateX(6px);
                white-space: nowrap;
                background: #1c1917;
                color: #fff;
                font-size: 12.5px;
                font-weight: 500;
                padding: 7px 12px;
                border-radius: 9px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease, transform 0.25s ease;
            }

            .wa-float__tip::after {
                content: '';
                position: absolute;
                right: -5px;
                top: 50%;
                transform: translateY(-50%);
                border-left: 6px solid #1c1917;
                border-top: 5px solid transparent;
                border-bottom: 5px solid transparent;
            }

            .wa-float:hover .wa-float__tip {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }

            @media (max-width: 768px) {
                .wa-float {
                    right: 16px;
                    bottom: 84px; /* clear the mobile bottom nav */
                    width: 54px;
                    height: 54px;
                }
                .wa-float__tip { display: none; }
            }

            @media (prefers-reduced-motion: reduce) {
                .wa-float__ring { animation: none; }
            }
        </style>

        <script>
            (function () {
                var el = document.getElementById('waFloat');
                if (!el) return;
                var delay = (parseInt(el.getAttribute('data-delay'), 10) || 0) * 1000;
                setTimeout(function () { el.classList.add('is-visible'); }, delay);
            })();
        </script>
    @endif
@endif

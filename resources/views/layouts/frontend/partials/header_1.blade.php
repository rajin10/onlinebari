@use('App\Core\ShoppingCart\Facades\Cart')

<header class="app-header">
    <style>
        :root {
            --bg-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-2: #f85606;
            --accent: #ff6b6b;
            --accent-hover: #ff5252;
            --dark: #1a1a2e;
            --light: #ffffff;
            --muted: rgba(255, 255, 255, 0.9);
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 5px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced Top Info */
        .top-info {
            background: var(--dark);
            color: var(--muted);
            font-size: 13px;
            padding: 10px 0;
            position: relative;
            overflow: hidden;
        }

        .top-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--bg-2);
            animation: slideRight 3s infinite linear;
        }

        @keyframes slideRight {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .top-info .row {
            display: flex;
            justify-content: center;
            gap: 24px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .top-info .row div {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            transition: var(--transition);
        }

        .top-info .row div:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .top-info .row i {
            color: var(--accent);
            font-size: 14px;
        }

        .top-info a {
            color: inherit;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .top-info a:hover {
            color: var(--accent);
        }

        /* Enhanced Main Header */
        .main-header {
            background: #ffffff;
            position: relative;
            z-index: 1000;
            background: #ffffff;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 16px 24px;
            margin: 0 auto;
        }

        .hdr-left,
        .hdr-center,
        .hdr-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hdr-right {
            flex-shrink: 0;
        }

        /* Enhanced Logo */
        .logo-link {
            position: relative;
            display: block;
            transition: var(--transition);
        }

        .logo-link img {
            display: block;
            width: auto;
            max-height: 90px;
            height: 80px !important;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            transition: var(--transition);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
        }

        .logo-link:hover img {
            transform: scale(1.05) rotate(-2deg);
            filter: drop-shadow(0 8px 12px rgba(0, 0, 0, 0.2));
        }


        .logo-link:hover::after {
            width: 100%;
        }

        /* Enhanced Desktop Search */
        .search-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            max-width: 700px;
            position: relative;
        }

        .search-box {
            flex: 1;
            display: flex;
            position: relative;
        }

        .search-box input[type="search"] {
            flex: 1;
            height: 52px;
            padding: 0 60px 0 24px;
            border-radius: 26px;
            border: 2px solid transparent;
            outline: none;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: var(--light);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .search-box input[type="search"]:focus {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2);
        }

        .search-box input[type="search"]::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bg-2);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .search-box button:hover {
            transform: translateY(-50%) scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
        }

        .search-box button svg {
            stroke: var(--light);
            stroke-width: 2;
            width: 20px;
            height: 20px;
        }

        /* Enhanced Actions */
        .action {
            position: relative;
            width: auto;
            height: auto;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0;
            background: transparent;
            backdrop-filter: none;
            border: none;
            color: #111;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .action:hover {
            background: transparent;
            border-color: transparent;
            transform: none;
            box-shadow: none;
            cursor: pointer;
        }

        .action img {
            width: 30px;
            height: 30px;
            object-fit: contain;
            display: block;
        }

        /* Enhanced Badges */
        .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            z-index: 5;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            border-radius: 50%;

            background: transparent;
            /* ❌ remove color */
            color: #000;
            /* ✅ black text */

            font-size: 11px;
            font-weight: 700;

            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow: none;
            border: none;
        }

        /* Enhanced Hamburger */
        .hamburger .bars {
            width: 24px;
            height: 18px;
            position: relative;
            display: inline-block;
        }

        .hamburger .bars span {
            display: block;
            height: 2px;
            width: 100%;
            background: #111;
            border-radius: 2px;
            position: absolute;
            left: 0;
            transform-origin: center;
            transition:
                top 0.55s cubic-bezier(0.25, 0.8, 0.25, 1),
                transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1),
                opacity 0.35s ease,
                width 0.4s ease;
        }

        .hamburger .bars .b1 {
            top: 0;
        }

        .hamburger .bars .b2 {
            top: 8px;
        }

        .hamburger .bars .b3 {
            top: 16px;
        }

        .hamburger.is-active .bars .b1 {
            top: 8px;
            transform: rotate(225deg) scale(1.05);
        }

        .hamburger.is-active .bars .b2 {
            opacity: 0;
            width: 0;
            transform: translateX(12px) scaleX(0);
        }

        .hamburger.is-active .bars .b3 {
            top: 8px;
            transform: rotate(-225deg) scale(1.05);
        }

        /* Enhanced Mobile Search Overlay */
        .mobile-search-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 12000;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .mobile-search-overlay.open {
            display: flex;
        }

        .mobile-search-panel {
            width: 100%;
            max-width: 720px;
            background: var(--dark);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            gap: 12px;
            align-items: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-lg);
            transform: translateY(20px);
            animation: slideUp 0.3s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
            }
        }

        .mobile-search-panel input {
            flex: 1;
            height: 56px;
            border-radius: 12px;
            padding: 0 20px;
            border: 2px solid transparent;
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
            font-size: 16px;
            transition: var(--transition);
        }

        .mobile-search-panel input:focus {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.2);
        }

        #mobileSearchClose {
            background: var(--bg-2);
            position: absolute;
            right: -16px;
            top: -23px;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--light);
            transition: var(--transition);
        }

        #mobileSearchClose:hover {
            transform: rotate(90deg);
            background: var(--accent);
        }

        /* Search trigger button (icon opens the search overlay on all breakpoints) */
        #mobileSearchBtn {
            width: 42px;
            height: 42px;
            display: inline-flex;
        }

        /* Responsive */
        .mobile-only {
            display: none;
        }

        .desktop-only {
            display: flex;
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }

            .mobile-only {
                display: flex !important;
            }

            .hdr-left {
                min-width: auto;
            }

            .hdr-center {
                flex: 1;
                justify-content: flex-start;
            }

            .hdr-right {
                gap: 8px;
            }

            .logo-link img {
                max-height: 44px;
            }

            .header-row {
                padding: 10px 14px;
            }

            .action,
            .hamburger {
                width: 30px;
                height: 30px;
            }

            .mobile-cart-openar {
                display: block;
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
            }

            .mobile-cart-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 9999px;
                background: rgba(0, 0, 0, 0.06);
                color: #111;
                text-decoration: none;
            }

            .hdr-right>a[title="Cart"] {
                display: none !important;
            }

            .top-info .row {
                gap: 12px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .top-info .row div {
                font-size: 11px;
                padding: 3px 8px;
            }

            #mobileSearchBtn {
                width: 30px;
                height: 30px;
            }

            .badge {
                top: -5px;
                right: -9px;
            }
        }

        @media (min-width: 769px) {
            .hdr-left {
                min-width: 180px;
                flex-shrink: 0;
            }

            .hdr-center {
                flex: 1;
                min-width: 0;
                justify-content: center;
            }

            .hdr-right {
                min-width: 0;
                flex-shrink: 0;
                justify-content: flex-end;
                gap: 20px;
            }
        }

        /* Cart Count Animation */
        @keyframes cartBounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        .badge.updated {
            animation: cartBounce 0.5s ease;
        }

        /* Floating Cart Notification */
        .cart-notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--bg-2);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            z-index: 10000;
            display: none;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .cart-notification.show {
            display: flex;
        }

        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1200;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(0);
            -webkit-backdrop-filter: blur(0);
            transition:
                background 0.35s ease,
                box-shadow 0.35s ease,
                backdrop-filter 0.35s ease,
                transform 0.35s ease;
            will-change: transform, background, box-shadow;
        }

        .main-header.is-sticky {
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(0);
        }

        .main-header .header-row {
            transition: padding 0.35s ease;
        }

        .main-header.is-sticky .header-row {
            padding: 10px 24px;
        }

        .main-header .logo-link img {
            transition: height 0.35s ease, max-height 0.35s ease, transform 0.35s ease, filter 0.35s ease;
        }

        .main-header.is-sticky .logo-link img {
            height: 66px !important;
            max-height: 76px;
            transform: scale(0.98);
            filter: drop-shadow(0 6px 10px rgba(0, 0, 0, 0.12));
        }

        @media (max-width: 768px) {
            .main-header {
                z-index: 1300;
            }

            .main-header.is-sticky .logo-link img {
                height: 38px !important;
                max-height: 40px;
            }

            .main-header.is-sticky .header-row {
                padding: 5px 8px;
            }
        }

        /* ===== STICKY EFFECT FINAL ===== */

        .main-header {
            transition: all 0.35s ease;
        }

        /* Sticky state */
        .main-header.is-sticky {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        /* Header height shrink */
        .main-header.is-sticky .header-row {
            padding: 10px 24px !important;
        }

        /* Logo shrink */
        .main-header.is-sticky .logo-link img {
            height: 66px !important;
            /* 🔥 adjust 10-14px smaller */
            max-height: 70px;
            transform: scale(0.95);
        }

        /* FORCE ICON SIZE (override all previous rules) */
        .header-row .action img {
            width: 28px !important;
            height: 28px !important;
        }

        /* Mobile override */
        @media (max-width: 768px) {
            .header-row .action img {
                width: 26px !important;
                height: 26px !important;
            }
        }

        /* ===== HEADER SMALL SAFE OVERRIDE ===== */

        /* First load: fully solid white */
        .main-header:not(.is-sticky) {
            background: #ffffff !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        /* Default header-row padding */
        .main-header:not(.is-sticky) .header-row {
            padding: 16px 24px !important;
        }

        .main-header:not(.is-sticky) .logo-link img {
            height: 68px !important;
            max-height: 72px !important;
        }

        /* Sticky: slightly tighter */
        .main-header.is-sticky .header-row {
            padding: 10px 24px !important;
        }

        .main-header.is-sticky .logo-link img {
            height: 58px !important;
            max-height: 62px !important;
        }

        /* Mobile */
        @media (max-width: 768px) {

            .main-header:not(.is-sticky) .header-row,
            .main-header.is-sticky .header-row {
                padding: 10px 14px !important;
            }

            .main-header:not(.is-sticky) .logo-link img {
                height: 40px !important;
                max-height: 42px !important;
            }

            .main-header.is-sticky .logo-link img {
                height: 36px !important;
                max-height: 38px !important;
            }
        }



        /* ===== SOLID WHITE HEADER — FINAL OVERRIDES ===== */

        /* Fixed positioning */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1200;
            border-bottom: 1px solid #EDE8E1;
            transition: box-shadow 0.28s ease, border-color 0.28s ease, backdrop-filter 0.28s ease;
            will-change: box-shadow;
        }

        /* Always solid white — same specificity as above, comes last, wins */
        .main-header:not(.is-sticky) {
            background: #ffffff !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        .main-header:not(.is-sticky):hover {
            background: #ffffff !important;
        }

        /* Sticky: glass shimmer */
        .main-header.is-sticky {
            background: rgba(255, 255, 255, 0.97) !important;
            border-bottom-color: transparent;
            box-shadow: 0 4px 24px rgba(28, 25, 23, 0.10) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
        }

        /* Icons: always dark — no invert filter */
        .main-header .action img,
        .main-header .hamburger .bars span,
        .main-header:not(.is-sticky) .action img,
        .main-header:not(.is-sticky) .hamburger .bars span,
        .main-header:not(.is-sticky):hover .action img,
        .main-header:not(.is-sticky):hover .hamburger .bars span {
            filter: none !important;
        }

        /* Cart badge: visible orange */
        .main-header .badge {
            background: #E85E0A !important;
            color: #fff !important;
        }

        /* Nav center column */
        .hdr-center {
            flex: 1;
            justify-content: center;
            overflow: hidden;
        }

        /* Show hamburger whenever the desktop nav is hidden (769–991px gap) */
        @media (max-width: 991px) {
            .hdr-center {
                display: none !important;
            }

            .hamburger {
                display: inline-flex !important;
            }
        }

        .hdr-center .main-menu-desktop,
        .hdr-center .main-menu-desktop .container,
        .hdr-center .nav-bar,
        .hdr-center .nav-menus {
            width: auto !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        /* Push page content below fixed header */
        .site-inner {
            padding-top: 68px;
        }

        @media (max-width: 767px) {
            .site-inner {
                padding-top: 58px !important;
            }
        }

        /* ============================================================
           PREMIUM HEADER REFINEMENTS (logo · hamburger · mini-cart)
        ============================================================ */

        /* Crisper, larger logo with comfortable padding */
        .logo-link {
            display: inline-flex;
            align-items: center;
            padding: 2px 0;
        }

        .logo-link img {
            image-rendering: -webkit-optimize-contrast;
            border-radius: 0;
            background: transparent;
            filter: none;
        }

        .main-header:not(.is-sticky) .logo-link img {
            height: 78px !important;
            max-height: 84px !important;
        }

        .main-header.is-sticky .logo-link img {
            height: 62px !important;
            max-height: 66px !important;
        }

        @media (max-width: 768px) {
            .main-header:not(.is-sticky) .logo-link img {
                height: 50px !important;
                max-height: 54px !important;
            }

            .main-header.is-sticky .logo-link img {
                height: 44px !important;
                max-height: 48px !important;
            }
        }

        /* Mobile hamburger (opens existing mobile sidebar) */
        .hdr-hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            width: 40px;
            height: 40px;
            padding: 0 8px;
            border: 0;
            background: transparent;
            cursor: pointer;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .hdr-hamburger:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .hdr-hamburger span {
            display: block;
            height: 2px;
            width: 22px;
            background: #1c1917;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        @media (max-width: 991px) {
            .hdr-hamburger {
                display: inline-flex !important;
            }
        }

        @media (min-width: 992px) {
            .hdr-hamburger {
                display: none !important;
            }
        }

        /* Tap-friendly action targets */
        .hdr-right .action {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .hdr-right .action:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        /* ---- Mini-cart dropdown ---- */
        .cart-wrap {
            position: relative;
        }

        .mini-cart {
            position: absolute;
            top: calc(100% + 14px);
            right: -6px;
            width: 360px;
            max-width: 92vw;
            background: #fff;
            border: 1px solid #efeae3;
            border-radius: 14px;
            box-shadow: 0 18px 50px rgba(28, 25, 23, 0.16);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: opacity 0.22s ease, transform 0.22s ease, visibility 0.22s;
            z-index: 1400;
            overflow: hidden;
        }

        .mini-cart::before {
            content: '';
            position: absolute;
            top: -7px;
            right: 22px;
            width: 14px;
            height: 14px;
            background: #fff;
            border-left: 1px solid #efeae3;
            border-top: 1px solid #efeae3;
            transform: rotate(45deg);
        }

        .cart-wrap.open .mini-cart {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .mini-cart__loading {
            padding: 28px;
            text-align: center;
            color: #9a938b;
            font-size: 13px;
        }

        .mini-cart__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-bottom: 1px solid #f1ece5;
        }

        .mini-cart__title {
            font-size: 15px;
            font-weight: 600;
            color: #1c1917;
        }

        .mini-cart__count {
            font-size: 12px;
            color: #8a837b;
            background: #f6f3ef;
            padding: 2px 9px;
            border-radius: 999px;
        }

        .mini-cart__items {
            max-height: 320px;
            overflow-y: auto;
            padding: 6px 8px;
        }

        .mini-cart__item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 8px;
            border-radius: 10px;
            transition: background 0.15s ease;
        }

        .mini-cart__item:hover {
            background: #faf8f5;
        }

        .mini-cart__thumb {
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 9px;
            overflow: hidden;
            background: #f5f3f0;
            display: block;
        }

        .mini-cart__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mini-cart__meta {
            flex: 1;
            min-width: 0;
        }

        .mini-cart__name {
            display: block;
            font-size: 13.5px;
            font-weight: 500;
            color: #1c1917;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mini-cart__name:hover {
            color: #c87d2a;
        }

        .mini-cart__qty {
            font-size: 12px;
            color: #8a837b;
        }

        .mini-cart__line {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .mini-cart__subtotal {
            font-size: 13px;
            font-weight: 600;
            color: #1c1917;
            white-space: nowrap;
        }

        .mini-cart__remove {
            border: 0;
            background: transparent;
            color: #c0b9b0;
            font-size: 17px;
            line-height: 1;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .mini-cart__remove:hover {
            color: #e23b3b;
        }

        .mini-cart__foot {
            padding: 14px 16px;
            border-top: 1px solid #f1ece5;
            background: #fbfaf8;
        }

        .mini-cart__total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            color: #1c1917;
            margin-bottom: 12px;
        }

        .mini-cart__actions {
            display: flex;
            gap: 10px;
        }

        .mini-cart__btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mini-cart__btn--ghost {
            border: 1.5px solid #e4ded6;
            color: #44403c;
            background: #fff;
        }

        .mini-cart__btn--ghost:hover {
            border-color: #1c1917;
            color: #1c1917;
        }

        .mini-cart__btn--solid {
            background: #1c1917;
            color: #fff;
        }

        .mini-cart__btn--solid:hover {
            background: #c87d2a;
            color: #fff;
        }

        .mini-cart__empty {
            padding: 34px 20px;
            text-align: center;
            color: #9a938b;
        }

        .mini-cart__empty p {
            margin: 10px 0 16px;
            font-size: 14px;
        }

        .mini-cart__empty .mini-cart__btn {
            display: inline-flex;
            padding: 0 22px;
            flex: none;
        }

        @media (max-width: 991px) {
            .mini-cart {
                display: none !important;
            }
        }

        /* Cart badge bounce on update */
        .badge.cart-count.updated {
            animation: cartBounce 0.5s ease;
        }

        /* Fallback content offset (JS sets the exact value at runtime) */
        .site-inner {
            padding-top: 96px;
        }

        @media (max-width: 767px) {
            .site-inner {
                padding-top: 74px !important;
            }
        }
    </style>

    <!--{{-- Optional Top info (desktop only) --}}
    <div class="top-info desktop-only">
        <div class="container">
            <div class="row">
                <div><i class="fal fa-phone-alt"></i> Call: <a href="tel:{{ setting('SITE_INFO_PHONE') }}">{{ setting('SITE_INFO_PHONE') }}</a></div>
                <div><i class="fal fa-envelope"></i> <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></div>
                <div><i class="fal fa-truck"></i> Free Shipping on orders over $50</div>
            </div>
        </div>
    </div>-->

    {{-- ===========================
   Final header: server-rendered mobile sidebar + restored mobile search + fixed accordion
   Replace previous header block with this
   =========================== --}}

    {{-- Main header --}}
    <div class="main-header">
        <div class="header-row container">

            {{-- LEFT: hamburger (mobile) + Logo --}}
            <div class="hdr-left">
                <button type="button" class="hdr-hamburger mobile-only" data-open-mobile-sidebar
                    aria-label="Open menu">
                    <span></span><span></span><span></span>
                </button>

                <a class="logo-link" href="{{ route('home') }}" aria-label="Go to homepage">
                    <img src="{{ asset('uploads/setting/' . setting('logo')) }}"
                        alt="{{ setting('site_title') ?? config('app.name', 'Store') }}"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
                    <span
                        style="display:none; font-weight:700; font-size:18px; letter-spacing:-0.3px; color:#1C1917;">{{ setting('site_title') ?? config('app.name', 'Store') }}</span>
                </a>
            </div>


            {{-- CENTER: desktop navigation menu --}}
            <div class="hdr-center desktop-only">
                @if (!empty(setting('MAIN_MENU_STYLE')))
                    @include('layouts.frontend.partials.partial-part.header_main_menu_' . setting('MAIN_MENU_STYLE'))
                @else
                    @include('layouts.frontend.partials.partial-part.header_main_menu_1')
                @endif
            </div>

            {{-- RIGHT: action icons --}}
            <div class="hdr-right">
                {{-- Search --}}
                <button type="button" class="action" id="mobileSearchBtn" aria-label="Search">
                    <img src="{{ asset('assets/frontend/images/search.png') }}" alt="Search"
                        style="width:22px; height:22px; object-fit:contain;">
                </button>

                {{-- Account (desktop only) --}}
                <a href="@auth{{ route('dashboard') }}@else{{ route('login') }} @endauth" class="action desktop-only"
                    aria-label="Account">
                    <img src="{{ asset('assets/frontend/images/user.png') }}" alt="Account"
                        style="width:22px; height:22px; object-fit:contain;">
                </a>

                {{-- Cart + mini-cart dropdown --}}
                <div class="cart-wrap" id="cartWrap">
                    <a href="{{ route('cart') }}" class="action cart-trigger" title="Cart" aria-label="Cart">
                        <img src="{{ asset('assets/frontend/images/cart-icon.png') }}" alt="Cart" style="width:24px;">
                        <span class="badge cart-count" id="cartCount">{{ Cart::count() }}</span>
                    </a>
                    <div class="mini-cart" id="miniCart" aria-hidden="true" role="dialog" aria-label="Mini cart">
                        <div class="mini-cart__inner" id="miniCartInner">
                            <div class="mini-cart__loading">Loading…</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- Mobile search overlay (restored original design) --}}
    <div id="mobileSearchOverlay" class="mobile-search-overlay" aria-hidden="true" role="dialog"
        aria-label="Mobile search" style="display:none;">
        <div class="mobile-search-panel" role="search"
            style="max-width:880px; margin:6vh auto 0; padding:18px; background:#fff; border-radius:10px; box-shadow:0 18px 40px rgba(2,6,23,0.2); position:relative;">
            <form action="{{ route('product.search') }}" method="GET" style="display:flex; width:100%;">
                <input id="mobileSearchInput" name="search" type="search" placeholder="Search products..."
                    aria-label="Search products" autofocus
                    style="flex:1; padding:12px 14px; border-radius:8px; border:1px solid #e6e9ef;">
                <button type="submit" aria-label="Search"
                    style="background:var(--bg-1, #2E6CB6); border-radius:12px; padding:12px 14px; border:none; margin-left:8px; color:#fff;">
                    <i class="fal fa-search" style="font-size:18px;"></i>
                </button>
            </form>

            <button id="mobileSearchClose" aria-label="Close search"
                style="position:absolute; top:10px; right:10px; background:transparent; border:0; font-size:18px; color:#6b7280;">
                <i class="fal fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Cart Notification --}}
    <div class="cart-notification" id="cartNotification"
        style="display:none; position:fixed; bottom:18px; right:18px; background:#2e7d32; color:#fff; padding:10px 14px; border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,0.18); z-index:14000;">
        <i class="fal fa-check-circle" style="margin-right:8px;"></i>
        <span id="cartNotificationText">Item added to cart!</span>
    </div>


    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                'use strict';

                /* Scrollbar-safe lock/unlock:
                   compute scrollbar width and apply padding-right so layout doesn't shift when overflow:hidden is applied */
                function safeLockBody(lock) {
                    try {
                        var html = document.documentElement;
                        var body = document.body;
                        if (!html || !body) return;
                        if (lock) {
                            var sbw = window.innerWidth - html.clientWidth;
                            html.style.overflow = 'hidden';
                            body.style.overflow = 'hidden';
                            if (sbw > 0) {
                                html.style.paddingRight = sbw + 'px';
                                body.style.paddingRight = sbw + 'px';
                            }
                        } else {
                            html.style.overflow = '';
                            body.style.overflow = '';
                            html.style.paddingRight = '';
                            body.style.paddingRight = '';
                        }
                    } catch (e) {
                        // Fail silently if anything unexpected
                        console.warn('safeLockBody error', e);
                    }
                }
                const hamburgerBtn = document.getElementById('hamburgerBtn');

                if (hamburgerBtn) {
                    hamburgerBtn.addEventListener('click', function() {
                        this.classList.toggle('is-active');
                        this.setAttribute('aria-expanded', this.classList.contains('is-active') ? 'true' :
                            'false');
                    });

                    document.addEventListener('mobile-sidebar-control', function(event) {
                        if (event.detail && event.detail.action === 'close') {
                            hamburgerBtn.classList.remove('is-active');
                            hamburgerBtn.setAttribute('aria-expanded', 'false');
                        }
                    });
                }
                /* -------------------
                   Mobile Search overlay handlers (guarded)
                   ------------------- */
                (function() {
                    var btn = document.getElementById('mobileSearchBtn');
                    var overlay = document.getElementById('mobileSearchOverlay');
                    var close = document.getElementById('mobileSearchClose');
                    var input = document.getElementById('mobileSearchInput');

                    function openSearch() {
                        if (!overlay) return;
                        overlay.style.display = 'block';
                        overlay.setAttribute('aria-hidden', 'false');
                        safeLockBody(true);
                        if (input) setTimeout(function() {
                            input.focus();
                        }, 60);
                        // also signal sidebar to close in case it's open (component handles this event)
                        document.dispatchEvent(new CustomEvent('mobile-sidebar-control', {
                            detail: {
                                action: 'close'
                            }
                        }));
                    }

                    function closeSearch() {
                        if (!overlay) return;
                        overlay.style.display = 'none';
                        overlay.setAttribute('aria-hidden', 'true');
                        safeLockBody(false);
                    }

                    if (btn) {
                        // attach single listener; avoid cloning/tricky replacements that caused duplication earlier
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            openSearch();
                        });
                    }
                    if (close) {
                        close.addEventListener('click', function(e) {
                            e.preventDefault();
                            closeSearch();
                        });
                    }
                    if (overlay) {
                        overlay.addEventListener('click', function(e) {
                            if (e.target === overlay) closeSearch();
                        });
                    }
                    // ESC closes search
                    document.addEventListener('keydown', function(ev) {
                        if (ev.key === 'Escape') {
                            if (overlay && overlay.style.display !== 'none') closeSearch();
                            // tell sidebar to close as well
                            document.dispatchEvent(new CustomEvent('mobile-sidebar-control', {
                                detail: {
                                    action: 'close'
                                }
                            }));
                        }
                    });
                })();

                const mainHeader = document.querySelector('.main-header');

                function handleStickyHeader() {
                    if (!mainHeader) return;

                    if (window.scrollY > 40) {
                        mainHeader.classList.add('is-sticky');
                    } else {
                        mainHeader.classList.remove('is-sticky');
                    }
                }

                handleStickyHeader();
                window.addEventListener('scroll', handleStickyHeader, {
                    passive: true
                });

            }); /* DOMContentLoaded */
        </script>
    @endpush

    @push('js')
        <script>
            /* ---- Mini-cart dropdown (hover preview on desktop) ---- */
            (function () {
                var wrap = document.getElementById('cartWrap');
                var panel = document.getElementById('miniCart');
                var inner = document.getElementById('miniCartInner');
                if (!wrap || !panel || !inner) return;

                var loaded = false;
                var hideTimer = null;
                var isDesktop = function () { return window.matchMedia('(min-width: 992px)').matches; };

                function load(force) {
                    if (loaded && !force) return Promise.resolve();
                    return fetch('{{ route('mini-cart') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function (r) { return r.json(); })
                        .then(function (d) {
                            inner.innerHTML = d.html;
                            loaded = true;
                            document.querySelectorAll('.cart-count').forEach(function (el) { el.textContent = d.count; });
                        })
                        .catch(function () {});
                }

                // exposed so the add-to-cart handler can refresh after changes
                window.refreshMiniCart = function () { return load(true); };

                function open() { clearTimeout(hideTimer); load(false); wrap.classList.add('open'); panel.setAttribute('aria-hidden', 'false'); }
                function close() { wrap.classList.remove('open'); panel.setAttribute('aria-hidden', 'true'); }

                wrap.addEventListener('mouseenter', function () { if (isDesktop()) open(); });
                wrap.addEventListener('mouseleave', function () { if (isDesktop()) hideTimer = setTimeout(close, 220); });
                document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });

                // expose for the global add-to-cart handler to pop the preview
                window.openMiniCart = function () { if (isDesktop()) { load(true).then(open); } };
            })();
        </script>
    @endpush

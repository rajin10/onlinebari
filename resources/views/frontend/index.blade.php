@extends('layouts.frontend.app')
@push('meta')
    <meta property="og:image" content="{{ asset('uploads/setting/' . setting('auth_logo')) }}" />
@endpush
{{-- @section('title', setting('site_title')) --}}

@section('content')

    @php
        $pop = App\Models\Slider::where('is_pop', '1')->orderBy('id', 'desc')->first();
    @endphp

    @if (setting('SLIDER_LAYOUT_STATUS') != 0 || setting('SLIDER_LAYOUT_STATUS') == '')
        @if (!empty(setting('SLIDER_LAYOUT')))
            <!--================ slider Area =================-->
            @include('frontend.partial.slider_style_' . setting('SLIDER_LAYOUT'))
        @else
            @include('frontend.partial.slider_style_1')
            <!--================ / slider Area =================-->
        @endif
    @endif




    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');



        .slick-dots {
            padding: 10px;
        }

        /* =========================
                   Global Luxury Home Spacing
                ========================= */
        .lux-section-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 24px;
            color: #111;
        }

        /* =========================
                   Category Section
                ========================= */
        .lux-category {
            padding: 52px 0 28px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            border-bottom: 1px solid #eee;
        }

        .lux-category .container {
            max-width: 1180px;
        }

        .lux-category-wrap {
            display: flex;
            gap: 18px;
            overflow-x: auto;
            scrollbar-width: none;
            justify-content: flex-start;
        }

        .lux-category-wrap::-webkit-scrollbar {
            display: none;
        }

        .lux-cat-item {
            min-width: 220px;
            text-align: center;
            text-decoration: none;
            color: #000;
            transition: all 0.3s ease;
        }

        .lux-cat-item:hover {
            transform: translateY(-5px);
        }

        .lux-cat-img {
            width: 220px;
            height: 220px;
            overflow: hidden;
            border-radius: 12px;
            background: #f5f5f5;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .lux-cat-item:hover .lux-cat-img {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .lux-cat-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s ease;
        }

        .lux-cat-item:hover img {
            transform: scale(1.05);
        }

        .lux-cat-item span {
            display: block;
            margin-top: 16px;
            font-size: 15px;
            font-weight: 600;
            color: #111;
            letter-spacing: 0.5px;
            text-transform: capitalize;
        }

        /* =========================
                   Shop Header
                ========================= */
        .lux-shop-header {
            padding: 72px 20px 52px;
            text-align: center;
            background: #fff;
        }

        .lux-shop-header p {
            margin: 0 0 18px;
            color: #707070;
            font-size: 12px;
            letter-spacing: 7px;
            font-weight: 400;
        }

        .lux-shop-header h1 {
            margin: 0;
            color: #111;
            font-family: 'Cinzel Decorative', Georgia, serif;
            font-size: 52px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* =========================
                   Premium Product Section
                ========================= */
        .lux-products {
            width: 100%;
            padding: 0 0 66px;
            background: #fff;
        }

        .lux-products . {
            padding-left: 34px;
            padding-right: 34px;
        }

        /* Per-category sections: same lux card, laid out as a responsive grid. */
        .lux-product-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            padding: 0 16px 40px;
        }

        @media (min-width: 640px) {
            .lux-product-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lux-product-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 24px;
                padding: 0 34px 50px;
            }
        }

        .product-slider .slick-slide {
            padding: 0 10px;
        }

        .product-slider .slick-list {
            margin: 0 -10px;
        }

        .lux-product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .lux-product-thumb {
            position: relative;
            height: 540px;
            overflow: hidden;
            border-radius: 7px;
            background: #f5f5f5;
        }

        .lux-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .lux-product-info {
            padding: 13px 0 0;
        }

        .lux-info-top-row,
        .lux-variants-row,
        .lux-purchase-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .lux-info-top-row {
            align-items: flex-start;
        }

        .lux-category-label {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            color: #707070;
        }

        .lux-product-name {
            max-width: 210px;
            margin: 0;
            font-size: 19px;
            font-weight: 500;
            line-height: 1.16;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .lux-product-name a {
            color: #111;
            text-decoration: none;
        }

        .lux-wishlist-btn {
            margin-top: 3px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #000;
            font-size: 12px;
            line-height: 1.2;
            white-space: nowrap;
            cursor: pointer;
        }

        .lux-variants-row {
            align-items: center;
            margin-top: 8px;
        }

        .lux-rating {
            color: #fbc831;
            font-size: 17px;
            line-height: 1;
            letter-spacing: -1px;
        }

        .lux-rating-count {
            margin-left: 4px;
            color: #707070;
            font-size: 12px;
        }

        .lux-color-variants {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .lux-color {
            width: 19px;
            height: 19px;
            display: inline-block;
            border-radius: 50%;
            border: 2px solid #eee;
        }

        .lux-purchase-row {
            align-items: center;
            margin-top: 12px;
        }

        .lux-product-price {
            margin: 0;
            color: #111;
            font-size: 19px;
            font-weight: 500;
        }

        @keyframes rainbow-border {
            0% {
                border-color: #ff0000;
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            }

            16.67% {
                border-color: #ff7f00;
                box-shadow: 0 0 10px rgba(255, 127, 0, 0.5);
            }

            33.33% {
                border-color: #ffff00;
                box-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
            }

            50% {
                border-color: #00ff00;
                box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            }

            66.67% {
                border-color: #0000ff;
                box-shadow: 0 0 10px rgba(0, 0, 255, 0.5);
            }

            83.33% {
                border-color: #4b0082;
                box-shadow: 0 0 10px rgba(75, 0, 130, 0.5);
            }

            100% {
                border-color: #9400d3;
                box-shadow: 0 0 10px rgba(148, 0, 211, 0.5);
            }
        }

        .lux-add-to-cart {
            padding: 9px 18px;
            border: 1px solid #000;
            border-radius: 7px;
            background: #000;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
            animation: rainbow-border 3s infinite;
        }

        .lux-add-to-cart:hover {
            background: #333;
        }

        /* =========================
                   Old Homepage Category Product Slider Support
                ========================= */
        .slick-slides .slick-slide {
            padding: 0 12px;
        }

        /* =========================
                   Video Section
                ========================= */
        .lux-video-section {
            padding: 80px 0 !important;
            margin: 0 !important;
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%) !important;
        }

        .lux-video-wrap {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            gap: 60px;
            padding: 0 40px;
        }

        .lux-video-box-container {
            flex: 1;
            min-width: 0;
        }

        .lux-video-box {
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.4s ease;
        }

        .lux-video-box:hover {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            transform: translateY(-8px);
        }

        .lux-video-card {
            padding: 0 !important;
            flex: 1;
            text-align: left;
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .lux-video-card h2 {
            margin: 0 0 20px;
            font-size: 42px;
            font-weight: 700;
            color: #111;
            font-family: 'Cinzel Decorative', Georgia, serif;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .lux-video-card p {
            max-width: none;
            margin: 0 0 32px;
            color: #666;
            font-size: 16px;
            line-height: 1.7;
            letter-spacing: 0.3px;
        }

        .lux-video-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .lux-video-btn {
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .lux-video-btn-primary {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .lux-video-btn-primary:hover {
            background: #333;
            border-color: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .lux-video-btn-secondary {
            background: transparent;
            color: #000;
            border-color: #000;
        }

        .lux-video-btn-secondary:hover {
            background: #f0f0f0;
            color: #000;
            border-color: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 1024px) {
            .lux-video-wrap {
                flex-direction: column;
                gap: 40px;
                padding: 0 20px;
            }

            .lux-video-card h2 {
                font-size: 32px;
            }

            .lux-video-box video {
                height: 400px;
            }
        }

        @media (max-width: 640px) {
            .lux-video-section {
                padding: 40px 0 !important;
            }

            .lux-video-wrap {
                padding: 0 16px;
                gap: 24px;
            }

            .lux-video-card h2 {
                font-size: 24px;
            }

            .lux-video-card p {
                font-size: 14px;
            }

            .lux-video-buttons {
                flex-direction: column;
            }

            .lux-video-btn {
                width: 100%;
                text-align: center;
            }

            .lux-video-box video {
                height: 300px;
            }
        }

        /* =========================
           Premium Banner Showcase
        ========================= */
        .lux-banner-showcase {
            padding: 0 !important;
            margin: 0 !important;
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
        }
        .lux-banner-showcase-inner { width: 100%; margin: 0; }
        .lux-banner-card {
            position: relative;
            display: block;
            overflow: hidden;
            margin: 0;
            border-radius: 0;
            background: #0a0a0a;
        }
        .lux-banner-card img {
            width: 100%;
            min-height: 640px;
            display: block;
            object-fit: cover;
            transition: transform 0.9s ease;
        }
        .lux-banner-card:hover img { transform: scale(1.04); }
        .lux-banner-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, rgba(0,0,0,.80) 0%, rgba(0,0,0,.44) 55%, rgba(0,0,0,.10) 100%);
            z-index: 1;
        }
        .lux-banner-content {
            position: absolute;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            max-width: 540px;
            color: #fff;
            z-index: 2;
        }
        .lux-banner-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 22px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #c9a96e;
        }
        .lux-banner-eyebrow::before,
        .lux-banner-eyebrow::after {
            content: '';
            display: block;
            width: 28px;
            height: 1px;
            background: #c9a96e;
            opacity: 0.65;
        }
        .lux-banner-content h2 {
            margin: 0 0 18px;
            font-size: 60px;
            font-weight: 800;
            line-height: 1.04;
            font-family: 'Cinzel Decorative', Georgia, serif;
            letter-spacing: 1px;
            text-shadow: 0 2px 24px rgba(0,0,0,.45);
        }
        .lux-banner-desc {
            display: block;
            max-width: 380px;
            margin: 0 0 38px;
            font-size: 16px;
            line-height: 1.68;
            opacity: .86;
            font-weight: 300;
            letter-spacing: .3px;
        }
        .lux-banner-buttons { display: flex; gap: 14px; flex-wrap: wrap; }
        .lux-banner-buttons .btn-primary-glow {
            padding: 15px 34px;
            border: none;
            border-radius: 7px;
            background: linear-gradient(135deg, #e8651a, #c9531a);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all .3s ease;
            box-shadow: 0 4px 22px rgba(232,101,26,.42);
            text-transform: uppercase;
        }
        .lux-banner-buttons .btn-primary-glow:hover {
            background: linear-gradient(135deg, #ff7a30, #e8651a);
            box-shadow: 0 8px 30px rgba(232,101,26,.6);
            transform: translateY(-2px);
        }
        .lux-banner-buttons .btn-ghost-white {
            padding: 14px 32px;
            border: 1.5px solid rgba(255,255,255,.55);
            border-radius: 7px;
            background: rgba(255,255,255,.06);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all .3s ease;
            backdrop-filter: blur(8px);
            text-transform: uppercase;
        }
        .lux-banner-buttons .btn-ghost-white:hover {
            background: rgba(255,255,255,.15);
            border-color: rgba(255,255,255,.9);
            transform: translateY(-2px);
        }

        /* =========================
           Premium Newsletter — full-width
        ========================= */
        .lux-newsletter-section {
            position: relative;
            padding: 100px 24px;
            background: #080808 !important;
            overflow: hidden;
            text-align: center;
            /* break out of site-inner padding */
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
        }
        /* warm amber glow at centre */
        .lux-newsletter-section::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 900px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(201,169,110,.09) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }
        /* top gold hairline */
        .lux-newsletter-section::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(201,169,110,.4) 50%, transparent 100%);
        }
        .lux-newsletter-inner {
            position: relative;
            max-width: 580px;
            margin: 0 auto;
            z-index: 1;
        }
        .lux-newsletter-tag {
            display: inline-block;
            margin-bottom: 20px;
            padding: 5px 18px;
            border: 1px solid rgba(201,169,110,.38);
            border-radius: 100px;
            font-size: 10px;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: #c9a96e;
        }
        .lux-newsletter-inner h2 {
            margin: 0 0 14px;
            font-size: 46px;
            font-weight: 700;
            color: #fff !important;
            font-family: 'Cinzel Decorative', Georgia, serif;
            line-height: 1.15;
            letter-spacing: .5px;
        }
        .lux-newsletter-inner > p {
            margin: 0 0 36px;
            font-size: 15px;
            color: rgba(255,255,255,.46) !important;
            line-height: 1.75;
        }
        .lux-newsletter-form {
            display: flex;
            max-width: 460px;
            margin: 0 auto 14px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(201,169,110,.22);
            background: rgba(255,255,255,.04);
            box-shadow: 0 0 60px rgba(201,169,110,.07), inset 0 1px 0 rgba(255,255,255,.04);
        }
        .lux-newsletter-input {
            flex: 1;
            padding: 15px 18px;
            background: transparent;
            border: none;
            outline: none;
            color: #fff !important;
            font-size: 14px;
            min-width: 0;
        }
        .lux-newsletter-input::placeholder { color: rgba(255,255,255,.26); }
        .lux-newsletter-input:focus { background: rgba(255,255,255,.04); }
        .lux-newsletter-submit {
            padding: 15px 26px;
            background: linear-gradient(135deg, #e8651a 0%, #bf4c10 100%);
            border: none;
            color: #fff !important;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all .3s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .lux-newsletter-submit:hover {
            background: linear-gradient(135deg, #ff7830 0%, #e8651a 100%);
            box-shadow: 0 0 24px rgba(232,101,26,.45);
        }
        .lux-newsletter-note {
            font-size: 11.5px;
            color: rgba(255,255,255,.2) !important;
            margin: 0;
            letter-spacing: .3px;
        }
        /* bottom gold hairline separator before footer */
        .lux-newsletter-bottom-line {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.06) 50%, transparent 100%);
        }

        /* =========================
           Premium Dark Footer — full-width
        ========================= */
        footer.lux-prem-footer {
            background: #0c0c0c !important;
            background-color: #0c0c0c !important;
            padding: 72px 0 0 !important;
            color: #fff !important;
            /* break out of site-inner padding */
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
            display: block !important;
        }
        footer.lux-prem-footer * { box-sizing: border-box; }
        .lux-prem-footer-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 48px;
        }
        .lux-prem-footer-top {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1fr;
            gap: 56px;
            padding-bottom: 60px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        /* Site logo in footer */
        .lux-prem-footer-logo {
            display: block;
            margin-bottom: 16px;
            height: 48px;
            width: auto;
            object-fit: contain;
            object-position: left center;
        }
        .lux-prem-footer-brand .brand-name {
            font-size: 17px !important;
            font-weight: 700 !important;
            color: #fff !important;
            margin: 0 0 12px !important;
            font-family: 'Cinzel Decorative', Georgia, serif !important;
            letter-spacing: 1px;
            display: block;
        }
        .lux-prem-footer-brand > p {
            font-size: 13px !important;
            color: rgba(255,255,255,.4) !important;
            line-height: 1.78 !important;
            margin: 0 0 24px !important;
            max-width: 230px;
        }
        .lux-prem-social { display: flex; gap: 8px; flex-wrap: wrap; }
        .lux-prem-social a {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,.06) !important;
            border: 1px solid rgba(255,255,255,.09) !important;
            color: rgba(255,255,255,.55) !important;
            font-size: 12px;
            text-decoration: none !important;
            transition: all .25s ease;
        }
        .lux-prem-social a:hover {
            background: rgba(201,169,110,.16) !important;
            border-color: rgba(201,169,110,.42) !important;
            color: #c9a96e !important;
            transform: translateY(-2px);
        }
        /* Column headings */
        .lux-prem-footer-col h5 {
            font-size: 9.5px !important;
            font-weight: 700 !important;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #c9a96e !important;
            margin: 0 0 18px !important;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(201,169,110,.15);
        }
        .lux-prem-footer-col ul { list-style: none !important; padding: 0 !important; margin: 0 !important; }
        .lux-prem-footer-col li { margin-bottom: 10px !important; list-style: none !important; }
        .lux-prem-footer-col a {
            font-size: 13px !important;
            color: rgba(255,255,255,.38) !important;
            text-decoration: none !important;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .lux-prem-footer-col a::before {
            content: '';
            display: inline-block;
            width: 0;
            height: 1px;
            background: #c9a96e;
            transition: width .2s ease;
            vertical-align: middle;
        }
        .lux-prem-footer-col a:hover { color: #fff !important; padding-left: 4px; }
        .lux-prem-footer-col a:hover::before { width: 8px; }
        /* Bottom bar */
        .lux-prem-footer-bottom {
            max-width: 1240px;
            margin: 0 auto;
            padding: 20px 48px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            border-top: 1px solid rgba(255,255,255,.05);
        }
        .lux-prem-footer-bottom > span {
            font-size: 12px !important;
            color: rgba(255,255,255,.22) !important;
        }
        .lux-prem-footer-bottom-links { display: flex; gap: 20px; }
        .lux-prem-footer-bottom-links a {
            font-size: 12px !important;
            color: rgba(255,255,255,.22) !important;
            text-decoration: none !important;
            transition: color .2s ease;
        }
        .lux-prem-footer-bottom-links a:hover { color: rgba(255,255,255,.7) !important; }

        /* =========================
           Responsive
        ========================= */
        @media (max-width: 1100px) {
            .lux-prem-footer-top { grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 36px; }
            .lux-prem-footer-inner { padding: 0 32px; }
            .lux-prem-footer-bottom { padding: 20px 32px 24px; }
        }

        @media (max-width: 900px) {
            .lux-product-thumb { height: 360px; }
            .lux-banner-content h2 { font-size: 44px; }
            .lux-prem-footer-top { grid-template-columns: 1fr 1fr 1fr; gap: 28px; }
            .lux-prem-footer-brand { grid-column: 1 / -1; }
            .lux-prem-footer-brand > p { max-width: 100%; }
        }

        @media (max-width: 768px) {
            .lux-category { padding: 24px 0 10px; }
            .lux-category-wrap { padding: 0 10px; gap: 12px; }
            .lux-cat-item { min-width: 118px; }
            .lux-cat-img { width: 118px; height: 118px; }
            .lux-shop-header { padding: 50px 16px 38px; }
            .lux-shop-header p { font-size: 11px; letter-spacing: 4px; }
            .lux-shop-header h1 { font-size: 34px; }
            .lux-products { padding: 0 20px 50px; }
            .lux-products . { padding-left: 0; padding-right: 0; }
            .lux-product-thumb { height: 520px; }
            .lux-product-name { max-width: 220px; font-size: 24px; }
            .lux-rating { font-size: 18px; }
            .lux-color { width: 22px; height: 22px; }
            .lux-product-price { font-size: 22px; }
            .lux-add-to-cart { padding: 11px 22px; font-size: 14px; }
            .lux-video-section { padding: 35px 12px; }
            .lux-video-card { padding: 22px; border-width: 3px; }
            .lux-video-card h2 { font-size: 24px; }
            /* Banner */
            .lux-banner-card img { min-height: 480px; }
            .lux-banner-content { left: 5%; right: 5%; max-width: none; }
            .lux-banner-content h2 { font-size: 30px; }
            .lux-banner-desc { font-size: 14px; margin-bottom: 28px; }
            .lux-banner-buttons { flex-direction: column; max-width: 220px; }
            .lux-banner-buttons button { width: 100%; text-align: center; }
            /* Newsletter */
            .lux-newsletter-section { padding: 72px 20px; }
            .lux-newsletter-inner h2 { font-size: 28px; }
            .lux-newsletter-form { flex-direction: column; border-radius: 8px; }
            .lux-newsletter-submit { padding: 14px; border-radius: 0 0 8px 8px; }
            /* Footer */
            footer.lux-prem-footer { padding: 52px 0 0 !important; }
            .lux-prem-footer-inner { padding: 0 20px; }
            .lux-prem-footer-top { grid-template-columns: 1fr 1fr; gap: 24px; padding-bottom: 40px; }
            .lux-prem-footer-brand { grid-column: 1 / -1; }
            .lux-prem-footer-brand > p { max-width: 100%; }
            .lux-prem-footer-bottom { padding: 16px 20px 80px; flex-direction: column; align-items: flex-start; gap: 10px; }
            .lux-prem-footer-bottom-links { gap: 16px; }
        }

        @media (max-width: 480px) {
            .shop-category .cat-row .cat-item { width: 24%; }
            .lux-newsletter-inner h2 { font-size: 24px; }
            .lux-banner-content h2 { font-size: 26px; }
            .lux-banner-eyebrow { font-size: 9px; letter-spacing: 3px; }
        }

        @media (max-width: 768px) {

            .hero-slider,
            .hero-slider .slider,
            .hero-slider .slider img,
            .slider,
            .slider img {
                height: 58vh !important;
                max-height: 520px !important;
            }

            .lux-product-thumb {
                height: 390px !important;
            }
        }

        @media (max-width: 450px) {

            .hero-slider,
            .hero-slider .slider,
            .hero-slider .slider img,
            .slider,
            .slider img {
                height: 50vh !important;
                max-height: 420px !important;
            }

            .lux-product-thumb {
                height: 340px !important;
            }
        }

        /* ===== FULL WIDTH VIDEO SECTION ===== */
        .homepage-video-section,
        .video-section,
        .home-video-wrapper {
            background: transparent !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            max-width: 100% !important;
        }

        .homepage-video-section .container,
        .video-section .container,
        .home-video-wrapper .container {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .homepage-video-section video,
        .video-section video,
        .home-video-wrapper video,
        .homepage-video-section iframe,
        .video-section iframe,
        .home-video-wrapper iframe {
            width: 100% !important;
            display: block !important;
            border-radius: 0 !important;
        }

        /* keep category menu normal font */
        .nav-categories>li>a,
        .nav-categories>li>button.link-like {
            font-family: inherit !important;
        }

        /* active menu color */
        .main-header .nav-categories>li>a.active {
            color: #e7c873 !important;
        }


        /* ===== FINAL FULL WIDTH VIDEO + BANNER FIX ===== */
        .lux-video-section,
        .lux-banner-showcase {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
        }

        .lux-video-box,
        .lux-video-box video,
        .lux-banner-showcase-inner,
        .lux-banner-card,
        .lux-banner-card img {
            width: 100% !important;
            max-width: 100% !important;
        }


        /* ===== HOME CATEGORY + PRODUCT FULL WIDTH FIX ===== */
        .lux-category-section,
        .lux-product-section,
        .category-section,
        .product-section {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-category-section .container,
        .lux-product-section .container,
        .category-section .container,
        .product-section .container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-products-grid,
        .lux-category-grid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }


        /* ===== FORCE HOME CONTENT EDGE TO EDGE ===== */
        body:has(.lux-product-card) .container,
        body:has(.lux-product-card) . {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-category-wrapper,
        .lux-products-wrapper,
        .lux-product-grid,
        .lux-products-grid,
        .lux-category-grid,
        .lux-product-card {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .lux-product-grid,
        .lux-products-grid {
            gap: 0 !important;
        }
    </style>

    {{-- CATEGORY SECTION --}}
    <section class="lux-category">
        <div class="container">

            {{-- CATEGORY TITLE --}}
            <h3 class="lux-section-title text-center">
                All Categories
            </h3>

            <div class="lux-category-wrap">

                @foreach ($categories as $category)
                    <a href="{{ route('category.product', $category->slug) }}" class="lux-cat-item">

                        <div class="lux-cat-img">
                            <img src="{{ asset('uploads/category/' . $category->cover_photo) }}" alt="{{ $category->name }}">
                        </div>

                        <span>{{ $category->name }}</span>

                    </a>
                @endforeach

            </div>
        </div>
    </section>


    <!--Product area starts-->

    <!-- Product area starts -->
    @if (setting('LATEST_PRODUCT_STATUS') != 0 || setting('LATEST_PRODUCT_STATUS') == '')
        <section class="lux-products">
            <div class="lux-shop-header">
                <p>Robot-Crafted 3D Art</p>
                <h1>Cozy Lighting</h1>
            </div>
            <div class=" px-4">

                <div class="product-slider">
                    @foreach ($products as $product)
                        <x-lux-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!--Hridoy-->
    @if (!empty($homepage_category_products))
        @foreach ($homepage_category_products as $homepage_category)
            <div class="products">
                <div class="">
                    <h3 class="title"><span>{{ $homepage_category->name }}</span> <a
                            href="{{ url('category/' . $homepage_category->slug) }}">View All</a></h3>
                    <div class="lux-product-grid">
                        @forelse ($homepage_category->products->take(6) as $product)
                            <x-lux-product-card :product="$product" :category="$homepage_category->name" />
                        @empty
                            <x-product-empty-component />
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if (!empty($video))
        <section class="lux-video-section">
            <div class="lux-video-wrap">
                <div class="lux-video-box-container">
                    <div class="lux-video-box">
                        <video controls poster="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : '' }}">
                            <source src="{{ asset('storage/' . $video->video) }}">
                        </video>
                    </div>
                </div>

                <div class="lux-video-card">
                    <h2>{{ $video->title }}</h2>

                    @if ($video->description)
                        <p>{{ $video->description }}</p>
                    @endif

                    <div class="lux-video-buttons">
                        <button class="lux-video-btn lux-video-btn-primary">Watch Now</button>
                        <button class="lux-video-btn lux-video-btn-secondary">Learn More</button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (!empty($banners) && $banners->count())
        <section class="lux-banner-showcase">
            <div class="lux-banner-showcase-inner">
                @foreach ($banners as $banner)
                    <a href="{{ $banner->url ?: 'javascript:void(0)' }}" class="lux-banner-card">
                        <img src="{{ asset('uploads/banner/' . $banner->image) }}" alt="Banner">
                        <div class="lux-banner-content">
                            <div class="lux-banner-eyebrow">Premium Cozy Lighting</div>
                            <h2>Make Every Corner Glow</h2>
                            <span class="lux-banner-desc">Discover soft, elegant lighting crafted for modern homes and intimate spaces.</span>
                            <div class="lux-banner-buttons">
                                <button type="button" class="btn-primary-glow" onclick="window.location='{{ route('product') }}'">Shop Now</button>
                                <button type="button" class="btn-ghost-white" onclick="window.location='{{ route('categories_all') }}'">Explore Collection</button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Premium Newsletter --}}
    <section class="lux-newsletter-section">
        <div class="lux-newsletter-inner">
            <span class="lux-newsletter-tag">Join Our Community</span>
            <h2>Transform Your Space</h2>
            <p>Exclusive deals, styling tips, and the latest arrivals — delivered straight to your inbox.</p>
            <form id="subs" action="{{ route('subscription') }}" method="POST">
                @csrf
                <div class="lux-newsletter-form">
                    <input type="email" name="email" class="lux-newsletter-input" placeholder="Your email address" required>
                    <button type="submit" class="lux-newsletter-submit">Subscribe</button>
                </div>
            </form>
            <p class="lux-newsletter-note">No spam, ever. Unsubscribe anytime.</p>
        </div>
        <div class="lux-newsletter-bottom-line"></div>
    </section>

    {{-- Premium Dark Footer --}}
    <footer class="lux-prem-footer">
        <div class="lux-prem-footer-inner">
            <div class="lux-prem-footer-top">
                <div class="lux-prem-footer-brand">
                    @if(setting('logo'))
                        <img src="{{ asset('uploads/setting/' . setting('logo')) }}" alt="{{ setting('site_name', 'AnasLuxyWorld') }}" class="lux-prem-footer-logo">
                    @else
                        <div class="brand-name">AnasLuxyWorld</div>
                    @endif
                    <p>Premium cozy lighting crafted to elevate your space with elegance and warmth.</p>
                    <div class="lux-prem-social">
                        @if(!empty(setting('facebook')))
                            <a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty(setting('instagram')))
                            <a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty(setting('twitter')))
                            <a href="{{ setting('twitter') }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(!empty(setting('youtube')))
                            <a href="{{ setting('youtube') }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(!empty(setting('whatsapp')))
                            <a href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        @endif
                        @if(!empty(setting('linkedin')))
                            <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                    </div>
                </div>

                <div class="lux-prem-footer-col">
                    <h5>Product</h5>
                    <ul>
                        <li><a href="{{ route('product') }}">Shop All</a></li>
                        <li><a href="{{ route('categories_all') }}">Categories</a></li>
                        <li><a href="{{ route('product') }}">New Arrivals</a></li>
                    </ul>
                </div>

                <div class="lux-prem-footer-col">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="{{ route('blogs') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Support</a></li>
                        <li><a href="{{ route('track') }}">Track Order</a></li>
                    </ul>
                </div>

                <div class="lux-prem-footer-col">
                    <h5>Company</h5>
                    <ul>
                        <li><a href="{{ route('page', ['slug' => 'about']) }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('page', ['slug' => 'refund-policy']) }}">Refund Policy</a></li>
                        <li><a href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lux-prem-footer-bottom">
            <span>© {{ date('Y') }} AnasLuxyWorld. All rights reserved.</span>
            <div class="lux-prem-footer-bottom-links">
                <a href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy</a>
                <a href="{{ route('page', ['slug' => 'terms-and-conditions']) }}">Terms</a>
            </div>
        </div>
    </footer>
    <x-add-cart-modal />
    @include('components.cart-modal-attri')

    {{-- Catgory Collups and Expand System --}}
    @push('internal_css')
        .superCatHomeToggle{height:330px;overflow-y:hidden;}.superCatHomeToggle
        #superCatViewAll{bottom:0;}#superCatViewAll{position:absolute;bottom:-1.5rem;left:0;right:0;background:var(--MAIN_MENU_BG);color:var(--MAIN_MENU_ul_li_color);z-index:999;outline:none;}
    @endpush
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // var buttonElement = document.createElement('button');
                // buttonElement.id = 'superCatViewAll';
                // buttonElement.innerText = 'View All';
                var superCatElement = document.getElementById('superCat');
                // superCatElement.appendChild(buttonElement);

                superCatElement.classList.add('superCatHomeToggle');

                superCatElement.addEventListener('mouseenter', function() {
                    superCatElement.classList.remove('superCatHomeToggle');
                });

                superCatElement.addEventListener('mouseleave', function() {
                    superCatElement.classList.add('superCatHomeToggle');
                });

                // buttonElement.addEventListener('click', function () {
                //     superCatElement.classList.toggle('superCatHomeToggle');
                //     if (buttonElement.innerText === 'View All') {
                //         buttonElement.innerText = 'Close';
                //     } else {
                //         buttonElement.innerText = 'View All';
                //     }
                // });
            });
        </script>
    @endpush
    {{-- / Catgory Collups and Expand System --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ajax-lux-cart-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    let form = document.getElementById(btn.getAttribute('data-form-id'));
                    if (!form) return;

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new FormData(form)
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.querySelectorAll('.cart-count').forEach(function(el) {
                                if (data.count !== undefined) el.innerText = data.count;
                            });
                        })
                        .catch(err => console.log(err));
                });
            });
        });
    </script>

@endsection



@push('js')
    <script>
        $(document).ready(function() {
            $('.value-plus').on('click', function() {
                var divUpd = $(this).parent().find('.value'),
                    newVal = parseInt(divUpd.val(), 10) + 1;
                divUpd.val(newVal);
                $('input#qty').val(newVal);
            });

            $('.value-minus').on('click', function() {
                var divUpd = $(this).parent().find('.value'),
                    newVal = parseInt(divUpd.val(), 10) - 1;
                if (newVal >= 1) {
                    divUpd.val(newVal);
                    $('input#qty').val(newVal);
                }

            });
            $(document).ready(function() {

                if ($('.product-slider').length) {

                    $('.product-slider').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        rows: 1,
                        autoplay: true,
                        autoplaySpeed: 2500,
                        arrows: true,
                        dots: false,
                        speed: 600,
                        infinite: true,
                        cssEase: 'ease-in-out',

                        responsive: [{
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    rows: 2
                                }
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 3,
                                    rows: 2
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    rows: 1
                                }
                            }
                        ]
                    });

                }

            });

            $(document).on('submit', '#addToCart', function(e) {
                e.preventDefault();

                let url = $(this).attr('action');
                let type = $(this).attr('method');
                let btn = $(this);
                let formData = $(this).serialize();

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(btn).attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.alert != 'Congratulations') {

                            $.toast({
                                heading: 'Warning',
                                text: response.message,
                                icon: 'warning',
                                position: 'top-right',
                                stack: false
                            });
                        } else {

                            // Hridoy
                            loadCartOnCanvas()

                            $('span#total-cart-amount').text(response.subtotal);

                            $.toast({
                                heading: 'Congratulations',
                                text: response.message,
                                icon: 'success',
                                position: 'top-right',
                                stack: false
                            });

                            $('#cart-modal').modal('hide');
                        }

                    },
                    complete: function() {
                        $(btn).attr('disabled', false);
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            });
            $(document).on('submit', '#subs', function(e) {
                e.preventDefault();

                let url = $(this).attr('action');
                let type = $(this).attr('method');
                let btn = $(this);
                let formData = $(this).serialize();

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(btn).attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.alert != 'Congratulations') {

                            $.toast({
                                heading: 'Warning',
                                text: response.message,
                                icon: 'warning',
                                position: 'top-right',
                                stack: false
                            });
                        } else {
                            $('span#total-cart-amount').text(response.subtotal);

                            $.toast({
                                heading: 'Congratulations',
                                text: response.message,
                                icon: 'success',
                                position: 'top-right',
                                stack: false
                            });

                            $('#cart-modal').modal('hide');
                        }

                    },
                    complete: function() {
                        $(btn).attr('disabled', false);
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            })

        });

        $('.slider').slick({
            autoplay: true,
            autoplaySpeed: 4000,
            speed: 1200,
            fade: true,
            cssEase: 'ease-in-out',
            arrows: false,
            dots: false,
            pauseOnHover: false,
            infinite: true
        });



        $('.catplay').slick({
            slidesToShow: 7,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2500,
            arrows: false,
            speed: 500,
            infinite: true,
            cssEase: 'ease-in-out',
            touchThreshold: 100,
            responsive: [

                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    }
                },

            ]
        });
    </script>

    @if (env('FIREBASE_ON') == 1)
        <script src="https://www.gstatic.com/firebasejs/8.2.0/firebase.js"></script>
        <script>
            var firebaseConfig = {
                apiKey: env('FIREBASAE_apiKey'),
                authDomain: env('FIREBASAE_authDomain'),
                projectId: env('FIREBASAE_projectId'),
                storageBucket: env('FIREBASAE_storageBucket'),
                messagingSenderId: env('FIREBASAE_messagingSenderId'),
                appId: env('FIREBASAE_appId')
            };

            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();


            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route('save-token') }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            alert('Token saved successfully.');
                        },
                        error: function(err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });

                }).catch(function(err) {
                    console.log('User Chat Token Error' + err);
                });


            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
        </script>
    @endif

    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>
@endpush

@if ($pop)
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ $pop->url }}">
                        <div class="item">
                            <img src="{{ asset('uploads/slider/' . $pop->image) }}" />
                        </div>
                    </a>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endif

@if ($popBanner)
    <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="bannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{ $popBanner->url }}">
                        <div class="item">
                            <img src="{{ asset('uploads/banner/' . $popBanner->image) }}" />
                        </div>
                    </a>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endif

@push('internal_css')
    .dtrr {width: 260px;}
    @media(max-width:576px) {
    .oc.shop-category .cat-row a:nth-last-child(2) {display: none;}
    }
    @media(max-width:1199px) {
    .hero-slider.col-lg-9 {flex: 0 0 72% !important; max-width: 72% !important;}
    .hero-categories ul li:last-child {display: none;}
    .hero-categories ul li:nth-last-child(2) {display: none;}
    }
    @media(max-width:1000px) {
    .hero-categories ul li:last-child {display: block;}
    .hero-categories ul li:nth-last-child(2) {display: block;}
    .dtrr {display: none;}
    .hero-slider.col-lg-9 {flex: inherit !important; max-width: inherit !important;}
    }
@endpush


<section class="hero-area">
    <style>
        /* container */
        .hero-area .container {
            max-width: 100% !important;
            padding: 0 !important;
        }

        .hero-row {
            margin: 0 !important;
        }

        .hero-slider {
            padding: 0 !important;
        }



        /* Use Bootstrap row behaviour (no overriding display) */
        .hero-row {
            margin: 0;
        }

        /* Slider column */
        .hero-slider {
            padding: 0;
        }

        .hero-slider .slider img {
            width: 100%;
            height: 100vh !important;
            /* 🔥 full screen */
            object-fit: cover;
        }

        /* Banner column */
        .banner-section {
            padding: 0 12px;
            display: flex;
            flex-direction: column;
            /* desktop/tablet: stacked inside right column */
            gap: 15px;
        }

        .banner-section .banner-item img {
            width: 100%;
            height: 285px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
        }

        /* Tablet: make banners side-by-side and reduce heights */
        @media (max-width: 991px) {
            .hero-slider .slider img {
                height: 260px;
            }

            .banner-section {
                flex-direction: row;
                /* two banners side-by-side */
                gap: 10px;
                padding: 0 8px;
            }

            .banner-section .banner-item img {
                height: 160px;
            }
        }

        /* Mobile (xs): stack slider then show banners side-by-side smaller */
        @media (max-width: 576px) {
            .hero-row {
                /* ensure Bootstrap row wraps */
            }

            .hero-slider .slider img {
                height: 200px;
            }

            .banner-section {
                flex-direction: row;
                /* keep banners side-by-side on mobile */
                gap: 8px;
                padding: 0 6px;
            }

            .banner-section .banner-item {
                flex: 1 1 50%;
                min-width: 0;
                /* prevents overflow */
            }

            .banner-section .banner-item img {
                height: 100px;
            }
        }

        /* Small helper: avoid image hover transform interfering */
        .sub-slider img:hover {
            transform: none !important;
        }

        /* Premium slider effect */
        .slider .slick-slide {
            position: relative;
            overflow: hidden;
        }

        .slider .slick-slide img {

            transform: scale(1.05);
        }

        /* Active slide zoom animation */
        .slider .slick-current img {
            transform: scale(1.15);
        }

        /* Smooth fade */
        .slider .slick-slide {
            opacity: 0;
            transition: opacity 1s ease;
        }

        .slider .slick-current {
            opacity: 1;
        }

        /* optional overlay for luxury look */
        .slider .slick-slide::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            /* medium black overlay */
        }



        .slider .slick-current img {
            transform: scale(1.15);
        }

        /* fade smooth */
        .slider .slick-slide {
            opacity: 0;
            transition: opacity 1s ease;
        }

        .slider .slick-current {
            opacity: 1;
        }

        /* dark overlay (premium look) */
        .slider .slick-slide::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(0, 0, 0, 0.1),
                    rgba(0, 0, 0, 0.3));
        }

        /* mobile fix */
        @media(max-width: 768px) {
            .hero-slider .slider img {
                height: 65vh !important;
            }
        }

        .hero-area,
        .hero-area .container,
        .hero-area .,
        .hero-area .row,
        .hero-row,
        .hero-slider,
        .slideshow,
        .slider,
        .slider .slick-list,
        .slider .slick-track,
        .slider .slick-slide,
        .slider .slick-slide>div {
            margin: 0 !important;
            padding: 0 !important;
        }

        .hero-area {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            overflow: hidden;
        }

        body {
            overflow-x: hidden;
        }

        .hero-slider .slider img {
            border-radius: 0 !important;
            width: 100vw !important;
            height: 91vh !important;
            object-fit: cover !important;
        }
    </style>

    <div class="container">
        <div class="row hero-row align-items-stretch">

            <!-- Left Side - Slider -->
            <div class="col-12 hero-slider">
                <div class="slideshow">
                    <div class="slider slick-slides">
                        @foreach ($sliders as $slider)
                            <div>
                                <a href="{{ $slider->url }}">
                                    <img src="{{ asset('uploads/slider/' . $slider->image) }}" alt="Slider Image" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side - Latest 2 Banners -->


        </div>
    </div>
</section>

@push('internal_css')
    .sub-slider img:hover {transform: scale(1.1) !important;}
    .navbar_fixed {position: fixed; top: 0; left: 0; right: 0; z-index: 9999999;}
    .catplay .draggable {padding: 20px 0px;}
    .catplay .slick-slide {margin: 0px 5px;}
@endpush

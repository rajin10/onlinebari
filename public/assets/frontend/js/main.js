// public/js/site-menu.js
(function($){
    $(function(){

        // ---------- safe body lock that preserves scroll position ----------
        function lockBody() {
            try {
                var scrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
                // store
                $('body').data('scrollY', scrollY);
                // fix position & prevent background scroll
                document.body.style.position = 'fixed';
                document.body.style.top = -scrollY + 'px';
                document.body.style.width = '100%';
            } catch (e) {
                console.warn('lockBody error', e);
                $('body').addClass('msc-lock');
            }
        }

        function unlockBody() {
            try {
                var scrollY = $('body').data('scrollY') || 0;
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                // restore scroll
                window.scrollTo(0, parseInt(scrollY, 10) || 0);
                $('body').removeData('scrollY');
                $('body').removeClass('msc-lock');
            } catch (e) {
                console.warn('unlockBody error', e);
                $('body').removeClass('msc-lock');
            }
        }

        // elems
        var $mainMenu = $('.main-menu');
        var $overlay = $('#msc-overlay');      // main menu overlay
        var $filterOverlay = $('#filter-overlay'); // filter overlay (if used)
        var $hero = $('.hero-area');
        var $back = $('.back');

        // Ensure overlay exists and is single instance
        if ($overlay.length === 0) {
            $('body').append('<div id="msc-overlay" class="menu-overly" style="display:none;"></div>');
            $overlay = $('#msc-overlay');
        }

        // HAMBURGER opens menu — bind idempotently
        $(document).on('click', '.bars, [data-open-mobile-sidebar]', function(e){
            e.preventDefault();
            // show menu
            $mainMenu.addClass('menu-show');
            $overlay.show().addClass('ovshow');
            lockBody();
            if ($hero && $hero.length) $hero.hide();
        });

        // Overlay click closes any open overlay-driven UI (menu or filter)
        // Single handler (no nested binding)
        $overlay.on('click', function(){
            closeAllOverlays();
        });

        // back button
        $back.on('click', function(){
            closeAllOverlays();
        });

        // centralized close
        function closeAllOverlays() {
            // main menu
            if ($mainMenu.hasClass('menu-show')) {
                $mainMenu.removeClass('menu-show');
            }
            if ($overlay.length) {
                $overlay.removeClass('ovshow').hide();
            }
            if ($hero && $hero.length) $hero.show();
            unlockBody();

            // filter overlay (if used)
            if ($filterOverlay.length && $filterOverlay.hasClass('ovshow')) {
                $filterOverlay.removeClass('ovshow').hide();
                $('.side-bar').removeClass('sidebar-show');
                unlockBody();
            }
        }

        // FILTER open (kept similar but with safe overlay)
        $('#filter-open').off('click.filteropen').on('click.filteropen', function(e){
            e.preventDefault();
            $('.side-bar').addClass('sidebar-show');
            // ensure filter overlay exists and show it
            if ($filterOverlay.length === 0) {
                $('body').append('<div id="filter-overlay" class="menu-overly2" style="display:none;"></div>');
                $filterOverlay = $('#filter-overlay');
                $filterOverlay.on('click', function(){ // single binding
                    $('.menu-overly2').removeClass('ovshow').hide();
                    $('.side-bar').removeClass('sidebar-show');
                    unlockBody();
                });
            }
            $filterOverlay.show().addClass('ovshow');
            lockBody();
        });

        // Safety: ESC closes overlays
        $(document).on('keydown.siteMenu', function(e){
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeAllOverlays();
            }
        });

        // CLEANUP: ensure we do not bind duplicate handlers for ovshow clicks inside openers
        // (We used off/.on above to avoid duplicates.)

        // ==== Keep your other behaviour (tabs, slick initialisation, footer accordions etc.) below ====
        // I will reattach remaining behaviour from your original file, but cleaned for idempotence.

        // navbarFixed (kept as before)
        var nav_offset_top = $('header').height() + 50;
        function navbarFixed(){
            if ($('.header_area').length) {
                $(window).off('scroll.navbarFixed').on('scroll.navbarFixed', function(){
                    var scroll = $(window).scrollTop();
                    if (scroll >= nav_offset_top) $(".header_area").addClass("navbar_fixed");
                    else $(".header_area").removeClass("navbar_fixed");
                });
            }
        }
        navbarFixed();

        // cat/menu toggles (kept)
        $('header .main-menu .nav-bar .nav-menus').show();
        $('#cat').off('click').on('click', function(){
            $('header .main-menu .nav-bar .nav-menus').hide();
            $('header .main-menu .nav-bar .header-category-wrap').show();
            $('.main-menu .collpase-menu-open #menu.active').removeClass('active');
            $(this).addClass('active');
        });
        $('#menu').off('click').on('click', function(){
            $('header .main-menu .nav-bar .header-category-wrap').hide();
            $('header .main-menu .nav-bar .nav-menus').show();
            $('.main-menu .collpase-menu-open #cat.active').removeClass('active');
            $(this).addClass('active');
        });

        // product view filter buttons (kept)
        $('#list').off('click').on('click', function(){
            $('#grid-view').hide();
            $('#grid').removeClass('active');
            $(this).addClass('active');
            $('#list-view').show();
        });
        $('#grid').off('click').on('click', function(){
            $('#list-view').hide();
            $('#list').removeClass('active');
            $(this).addClass('active');
            $('#grid-view').show();
        });

        // footer items (kept)
        var winWidth = $(window).width();
        $('.footer-item .t1').off('click').on('click', function(){
            winWidth = $(window).width();
            if (winWidth < 768) $('.footer-item .ic1').slideToggle();
        });
        $('.footer-item .t2').off('click').on('click', function(){
            winWidth = $(window).width();
            if (winWidth < 768) $('.footer-item .ic2').slideToggle();
        });
        $('.footer-item .t3').off('click').on('click', function(){
            winWidth = $(window).width();
            if (winWidth < 768) $('.footer-item .ic3').slideToggle();
        });
        $('.footer-item .t4').off('click').on('click', function(){
            winWidth = $(window).width();
            if (winWidth < 768) $('.footer-item .ic4').slideToggle();
        });

        // submenu slide
        $('.submenu').off('click').on('click', function(){
            $(this).children('ul').slideToggle();
        });

        // slick autoplay (re-attaching - ensure .autoplay exists)
        if ($.fn.slick) {
            $('.autoplay').not('.slick-initialized').slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplaySpeed: 2000,
                speed: 500,
                infinite: true,
                cssEase: 'ease-in-out',
                touchThreshold: 100,
                autoplay: true,
                arrows: false,
                responsive:[
                    { breakpoint:1000, settings:{ slidesToShow:4 }},
                    { breakpoint:767, settings:{ slidesToShow:3 }},
                    { breakpoint:500, settings:{ slidesToShow:2 }}
                ]
            });
        }

    });
})(jQuery);

{{-- Footer bottom nav: Home / Account / Category / Cart (badge) --}}
@use('App\Core\ShoppingCart\Facades\Cart')

<style>
/* Mobile / footer nav styling (clean, center-aligned icons + labels) */
.mobile-bottom-nav {
    display: flex;
    justify-content: space-around;
    align-items: center;
    gap: 6px;
    padding: 10px 6px;
    background: #ffffff;
    border-top: 1px solid #e6e6e6;
    box-shadow: 0 -1px 6px rgba(0,0,0,0.03);
    font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    z-index: 1200;
    position:fixed;
    bottom:0;
    width:100%;
}

/* each item */
.mobile-bottom-nav__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #0f172a;
    font-size: 12px;
    min-width: 64px;
}

/* icon */
.mobile-bottom-nav__item i {
    font-size: 18px;
    line-height: 1;
}

/* label below icon */
.mobile-bottom-nav__label {
    margin-top: 4px;
    font-size: 12px;
    line-height: 1;
}

/* badge - small round */
.mobile-bottom-nav__badge {
    display: inline-block;
    min-width: 18px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 600;
    text-align: center;
    border-radius: 999px;
    background: #ef4444; /* red */
    color: #fff;
    margin-top: 6px;
}

/* visually hide badge when zero (optional) */
.mobile-bottom-nav__badge--hidden {
    display: none !important;
}

/* make anchors accessible and tappable */
.mobile-bottom-nav__link {
    width: 100%;
    padding: 6px 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    color:#1d312e;
}

.badge-foo{
    position:absolute;
    top:-6px;
    right:24px;
    min-width: 17px;
    height: 20px;
    padding: 0 6px;
    border-radius: 11px;
    background: var(--bg-2);
    color: var(--light);
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.3);
    animation: pulse 2s infinite;
    transition: var(--transition);
}
/* responsive tweak: on larger screens make items inline smaller */
@media (min-width: 992px) {
    .mobile-bottom-nav {
        display:none;
        padding: 8px 24px;
        max-width: 920px;
        margin: 0 auto;
    }
    .mobile-bottom-nav__item {
        min-width: 72px;
    }
}
</style>

<nav class="mobile-bottom-nav" role="navigation" aria-label="Footer quick navigation">
    {{-- Home --}}
    <div class="mobile-bottom-nav__item">
        <a class="mobile-bottom-nav__link" href="{{ route('home') }}" title="Home" aria-label="Home">
            <i class="fas fa-home" aria-hidden="true"></i>
            <span class="mobile-bottom-nav__label">Home</span>
        </a>
    </div>

    {{-- Account (if logged in) otherwise Login/Register dropdown --}}
    <div class="mobile-bottom-nav__item">
        @auth
            <a class="mobile-bottom-nav__link" href="{{ route('dashboard') }}" title="My Account" aria-label="My Account">
                <i class="fas fa-user" aria-hidden="true"></i>
                <span class="mobile-bottom-nav__label">Account</span>
            </a>
        @else
            <a class="mobile-bottom-nav__link" href="{{ route('login') }}" title="Login" aria-label="Login">
                <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                <span class="mobile-bottom-nav__label">Login</span>
            </a>
        @endauth
    </div>

    {{-- Category (opens mobile sidebar) --}}
    <div class="mobile-bottom-nav__item">
        {{-- Keep data-open-mobile-sidebar attribute so your existing sidebar component will respond --}}
        <a href="#" class="mobile-bottom-nav__link mobile-category-trigger bars" data-open-mobile-sidebar title="Categories" aria-label="Open categories">
            <i class="fas fa-bars" aria-hidden="true"></i>
            <span class="mobile-bottom-nav__label">Category</span>
        </a>
    </div>

    {{-- Cart (with badge) --}}
    <div class="mobile-bottom-nav__item">
            <a href="{{ route('cart') }}" class="mobile-bottom-nav__link" title="Cart" aria-label="Cart">
                <i class="fal fa-shopping-basket"></i>
                <span class="badge-foo" id="cartCount">{{ Cart::count() }}</span>
            <span class="mobile-bottom-nav__label">Cart</span>
            </a>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // When user clicks the Category item: try to use data-open-mobile-sidebar (if component bound directly)
    // and also dispatch programmatic event as fallback (ensures compatibility).
    document.querySelectorAll('.mobile-category-trigger').forEach(function(el){
        el.addEventListener('click', function (ev) {
            ev.preventDefault();

            // If element has the attribute, native listener might handle it, but dispatch anyway to be safe.
            try {
                // Preferred: dispatch custom event your sidebar listens to
                var evSidebar = new CustomEvent('mobile-sidebar-control', { detail: { action: 'open', opener: el } });
                document.dispatchEvent(evSidebar);
            } catch (e) {
                // fallback: try clicking the first element that has data-open-mobile-sidebar
                var fallback = document.querySelector('[data-open-mobile-sidebar]');
                if (fallback && typeof fallback.click === 'function') fallback.click();
            }
        });
    });

    // Expose a global function to update cart badge from AJAX code:
    window.updateFooterCartBadge = function (count) {
        try {
            var badge = document.getElementById('footerCartBadge');
            if (!badge) return;
            var n = parseInt(count, 10) || 0;
            badge.textContent = n;
            if (n <= 0) badge.classList.add('mobile-bottom-nav__badge--hidden');
            else badge.classList.remove('mobile-bottom-nav__badge--hidden');
        } catch (e) {
            console.warn('updateFooterCartBadge error', e);
        }
    };

    // Optional: keep badge in sync if some global event fires (hook into your add-to-cart success)
    // Example usage after AJAX add-to-cart:
    // window.updateFooterCartBadge(response.cart_count);

});
</script>

<footer>
    <div class="container" style="padding: 0px 20px !important;">
        <div class="row">
            <div class="footer-item  col-lg-3 col-md-3 col-sm-12">
                <li id="nav_menu-2" class="widget widget_nav_menu"> 
                    <div style="margin-bottom: 20px;" class="apps footer-logo">
                    <a href="{{route('home')}}">
                    <img src="{{asset('uploads/setting/'.setting('logo'))}}" alt="Application Logo" style="width: auto; height:80px">
                </a>
                </div>
                    <div class="item-content ic2">
                        <div class="menu-main-container">
                            <ul id="menu-main-18" class="menu">
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76">
                                    {{setting('footer_description')}}
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </li>
            </div>
            <div class="footer-item  col-lg-3 col-md-3 col-sm-12">
                <li id="nav_menu-2" class="widget widget_nav_menu">
                    <div class="title t1">
                        <span>Info</span>
                        <span class="footer-sub-icon"><i class="icofont icofont-simple-right"></i></span>
                    </div>
                    <div class="item-content ic1">
                        <div class="menu-main-container">
                            <ul id="menu-main-18" class="menu">
                                @foreach($footerPages as $page)
                                <li><a href="{{route('page',['slug'=>$page->name])}}"> {{$page->name}}</a></li>
                                @endforeach
                                @foreach(App\Models\Page::where('position',2)->where('status',1)->get() as $page)
                                <li><a href="{{route('page',['slug'=>$page->name])}}"> {{$page->name}}</a></li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </li>
            </div>
            <style>
.whatsapp-fixed{
    position:fixed;
    right:20px;
    bottom:20px;
    z-index:9999;
    display:flex;
    align-items:center;
    justify-content:center;
    width:60px;
    height:60px;
    border-radius:50%;
    background:#25D366;
    box-shadow:0 8px 20px rgba(0,0,0,.25);
}

.whatsapp-fixed svg{
    width:36px;
    height:36px;
}

@media(max-width:768px){
    .whatsapp-fixed{
        right:5px;
        bottom:77px;
        width:45px;
        height:45px;
    }

}

                @media(max-width:767px) {
                    .item-content {
                        display: none;
                    }
                    
                    
                   footer-item .title span {
    background: transparent;
    color: #fff;
    margin: 0 !important;
    padding: 0 !important;
}
                }
                
                footer a:hover {
    color: #f85606;
    text-decoration: underline;
}
            </style>
@if(!empty(setting('whatsapp')))
<a href="https://wa.me/{{ setting('whatsapp') }}" class="whatsapp-fixed" target="_blank">
    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 48 48">
        <path fill="#fff" d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"/>
        <path fill="#40c351" d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"/>
        <path fill="#fff" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"/>
    </svg>
</a>
@endif

            <div class="footer-item  col-lg-3 col-md-3 col-sm-12">
                <li id="nav_menu-2" class="widget widget_nav_menu">
                    <div class="title t3">
                        <span>Menu</span>
                        <span class="footer-sub-icon"><i class="icofont icofont-simple-right"></i></span>
                    </div>
                    <div class="item-content ic3">
                        <div class="menu-main-container">
                            <ul id="menu-main-18" class="menu">
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('cart')}}"> Cart</a></li>
                                @auth
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('account')}}"> Account</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('order')}}"> Order</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('checkout')}}"> Checkout</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76">
                                    <a href="{{route('logout')}}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"> Log Out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                                @else
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('login')}}"> Login</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('register')}}"> Registration</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-76"><a
                                        href="{{route('vendorJoin')}}"> Vendor Register</a></li>
                                @endauth

                            </ul>
                        </div>
                    </div>
                </li>
            </div>


            <div class="footer-item  col-lg-3 col-md-3 col-sm-12">
                <li id="nav_menu-2" class="widget widget_nav_menu">
                    <div class="title t2">
                        <span>Conatct US</span>
                        <span class="footer-sub-icon"><i class="icofont icofont-simple-right"></i></span>
                    </div>
                    <div class="item-content ic2">
                        <div class="menu-main-container">
                            <ul style="opacity: 1;" id="menu-main-18" class="menu">
                                <li style="line-height: 22px;">Address:{{setting('SITE_INFO_ADDRESS')}}</li>
                                <li>Email: {{setting('SITE_INFO_SUPPORT_MAIL')}}</li>
                                <li>Contact No: {{setting('SITE_INFO_PHONE')}}</li>
                                <li><a style="font-weight: 700;border-radius: 5px;padding: 5px 15px;display: inline-block;background: #068f25; color:#fff;font-size: 16px;"
                                        href="{{route('connection.live.chat')}}"
                                        class="{{Request::is('connection') ? 'active':''}}"> Live Chat</a></li>
                            </ul>
                        </div>
                    </div>

                </li>
            {{-- </div>
            <div class="footer-item  col-lg-3 col-md-3 col-sm-12"> --}}
                <style>
                    #nav_menu-2 .aroow2 {
                        display: none;
                    }
                </style>
                <li id="nav_menu-2" class="widget widget_nav_menu pt-3">
                    <div class="title t4">
                        <span>Get In Touch</span>
                        <span class="footer-sub-icon"><i class="icofont icofont-simple-right"></i></span>
                    </div>

                    <ul style="margin-top: 0;" class="item-content  ic4">
                        @if(!empty(setting('facebook')))
                        <li class="s-l-i-3"><a href="{{setting('facebook')}}"><i style="background:#3b5997 ;"
                                    class="icofont icofont-social-facebook"></i></a></li>
                        @endif
                        @if(!empty(setting('instagram')))
                        <li class="s-l-i-3"><a href="{{setting('instagram')}}"><i style="background:#e24667 ; padding:10px;"
                                    class="fab fa-instagram"></i></a></li>
                        @endif
                        @if(!empty(setting('messanger')))
                        <li class="s-l-i-3"><a href="{{setting('messanger')}}"><i style="background:#3b5997 ;"
                                    class="fab fa-facebook-messenger"></i></a></li>
                        @endif
                        @if(!empty(setting('youtube')))
                        <li class="s-l-i-3"><a href="{{setting('youtube')}}"><i style="background:#ff0000 ;"
                                    class="icofont icofont-youtube-play"></i></a></li>
                        @endif

                        @if(!empty(setting('whatsapp')))
                        <li class="s-l-i-3"><a href="https://wa.me/{{setting('whatsapp')}}"><i style="background:#439665 ;"
                                    class="icofont icofont-social-whatsapp"></i></a></li>
                        @endif
                        @if(!empty(setting('twitter')))
                        <li class="s-l-i-3"><a href="{{setting('twitter')}}"><i style="background:#21a1f0 ;"
                                    class="icofont icofont-social-twitter"></i></a></li>
                        @endif
                        @if(!empty(setting('linkedin')))
                        <li class="s-l-i-3"><a href="{{setting('linkedin')}}"><i style="background:#21a1f0 ;"
                                    class="icofont icofont-social-linkedin"></i></a></li>
                        @endif
                    </ul>
                    @if(setting('android_app'))
                    <div class="platform item-content  ic4" style="margin-top: 20px;">
                        <div class="title t1" style="margin-bottom: 8px !important">
                            <span>Download Now!</span>
                        </div>
                        <ul style="margin-top: 0;">
                            <li class="s-l-i-3"><a style="opacity: 1;"
                                    href="https://drive.google.com/file/d/16neRUFZf20QHgGXxtjFZdGAqU3kxr492/view?usp=drivesdk"><img
                                        style="width:165px;border-radius: 5px;border: 1px solid gainsboro;"
                                        src="{{asset('/')}}/assets/uploads/images/google-play-png-logo-3799.png"
                                        alt=""></a></li>
                        </ul>
                    </div>
                    @endif
                </li>
                
                @php
                    echo setting('FOOTER_COL_4_HTML');
                @endphp
                
            </div>
        </div>
        
    </div>
    
      
    <br>
    
    <div class="copy " style="background-color:#28436c; padding: 10px !important;text-align: center;">
        <div class="container" style="padding: 0px 20px !important;">
            <div class="copy-rihgt-1">
               
                
                    <p class="text-center col-12">
                        <a target="_blank" href="https://eurekaavenue.com/" style="color:white;text-decoration:none;text-align:center;d isplay:block; margin:0; color:white;">
                           © Onlinebari. All Rights Reserved<span id="year"></span>
                        </a>
                    </p>
                
                                
            </div>
        </div>
    </div>
</footer>

    <style>
        .body {
            background-color: green !important;
            background: green !important;
        }
    </style>
    
    <script>
        // Get the current year dynamically
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>


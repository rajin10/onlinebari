{{-- resources/views/components/desktop-menu.blade.php --}}
<div class="main-menu-desktop">
    <div class="container" style="padding: 0 20px;">
        <div class="nav-bar">
            <style>
                :root {
                    --menu-bg: #22385a;
                    --menu-accent: #f85606;
                    --menu-text: #ffffff;
                    --item-bg: #ffffff;
                    --item-border: #e6e9ef;
                    --muted: #6b7280;
                }

                /* Desktop-only: hide this whole component on small screens */
                @media (max-width: 991px) {
                    .main-menu-desktop {
                        display: none !important;
                    }
                }

                header .main-menu-desktop {
                    background: var(--menu-bg);
                    color: var(--menu-text);
                    padding: 8px 0;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    position: relative;
                    z-index: 1000;
                    margin-top: 16px;
                }

                .main-menu-desktop .container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    max-width: 1200px;
                    margin: 0 auto;
                }

                /* Desktop nav wrapper */
                .nav-menus {
                    width: 100%;
                }

                /* Categories list */
                .nav-categories {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    gap: 2px;
                    align-items: center;
                    flex-wrap: nowrap;
                    justify-content: center;
                }

                .nav-categories>li {
                    display: inline-flex;
                    align-items: center;
                    position: relative;
                    border-radius: 6px;
                }

                .nav-categories>li>a,
                .nav-categories>li>button.link-like {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    padding: 4px 6px;
                    color: var(--menu-text);
                    text-decoration: none;
                    font-weight: 500;
                    white-space: nowrap;
                    font-size: 14px;
                    transition: background-color 0.2s ease;
                    border: none;
                    background: transparent;
                    cursor: pointer;
                }

                .nav-categories>li>a:hover,
                .nav-categories>li>button.link-like:hover {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 6px;
                }

                .nav-categories>li>a.active {
                    color: #f85606;
                    background: rgba(129, 140, 248, 0.1);
                }

                /* special for auth avatar */
                .authpro img {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    margin-right: 8px;
                    object-fit: cover;
                    border: 2px solid rgba(255, 255, 255, 0.2);
                }

                /* Chevron icon */
                .chev {
                    font-size: 10px;
                    margin-left: 4px;
                    opacity: 0.7;
                    transition: transform 0.2s ease;
                }

                .category-item:hover .chev,
                .category-item.desktop-open .chev {
                    transform: rotate(180deg);
                }

                /* Desktop submenu (hover) - FIXED: Now properly visible */
                .sub-menu {
                    list-style: none;
                    margin: 0;
                    padding: 8px 0;
                    position: absolute;
                    left: 0;
                    top: 100%;
                    background: #ffffff;
                    min-width: 250px;
                    border-radius: 8px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                    border: 1px solid #e5e7eb;
                    display: block;
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-5px);
                    transition: all 0.2s ease;
                    pointer-events: none;
                    z-index: 1001;
                }

                /* Make dropdown items visible */
                .sub-menu li a {
                    display: block;
                    padding: 10px 16px;
                    color: #374151;
                    text-decoration: none;
                    font-size: 14px;
                    transition: background-color 0.15s ease;
                    white-space: nowrap;
                }

                .sub-menu li a:hover {
                    background: #f3f4f6;
                    color: var(--menu-accent);
                }

                /* Show submenu on desktop hover - FIXED */
                @media (min-width: 992px) {
                    .category-item:hover>.sub-menu {
                        opacity: 1;
                        visibility: visible;
                        transform: translateY(0);
                        pointer-events: auto;
                    }
                }

                /* For click toggle functionality */
                .category-item.desktop-open>.sub-menu {
                    opacity: 1 !important;
                    visibility: visible !important;
                    transform: translateY(0) !important;
                    pointer-events: auto !important;
                }

                /* small UX touches */
                .nav-categories>li:focus-within {
                    outline: 2px solid var(--menu-accent);
                    outline-offset: 2px;
                    border-radius: 6px;
                }

                /* Add subtle separator */
                .nav-categories>li:nth-last-child(3)::before {
                    content: '';
                    position: absolute;
                    left: -1px;
                    top: 50%;
                    transform: translateY(-50%);
                    height: 20px;
                    width: 1px;
                    background: rgba(255, 255, 255, 0.2);
                }

                @media(max-width: 1100px) {
                    .main-menu-desktop .container {
                        padding: 0 16px;
                    }

                    .nav-categories>li>a,
                    .nav-categories>li>button.link-like {
                        padding: 10px 14px;
                        font-size: 13.5px;
                    }
                }

                /* ===== WHITE HEADER — DARK NAV TEXT ===== */

                /* Override the dark navy background and white text */
                :root {
                    --menu-bg: transparent;
                    --menu-text: #1C1917;
                }

                header .main-menu-desktop {
                    background: transparent;
                    padding: 0;
                    margin-top: 0;
                }

                .nav-categories>li>a,
                .nav-categories>li>button.link-like {
                    color: #1C1917 !important;
                }

                .nav-categories>li>a:hover,
                .nav-categories>li>button.link-like:hover {
                    color: #C87D2A !important;
                    background: rgba(200, 125, 42, 0.08) !important;
                }

                .nav-categories>li>a.active {
                    color: #C87D2A !important;
                    background: rgba(200, 125, 42, 0.08) !important;
                }

                .nav-categories>li:nth-last-child(3)::before {
                    background: rgba(28, 25, 23, 0.15);
                }

                .authpro img {
                    border-color: rgba(28, 25, 23, 0.15) !important;
                }
            </style>

            <div class="nav-menus">
                <ul class="nav-categories" id="navCategoriesDesktop">
                    <li><a href="{{ route('home') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('product') }}" class="{{ Request::is('product*') ? 'active' : '' }}">Shop</a>
                    </li>
                    <li><a href="{{ route('categories_all') }}"
                            class="{{ Request::is('categories_all*') || Request::is('category/*') || Request::is('sub-category/*') || Request::is('mini-category/*') || Request::is('extra-category/*') ? 'active' : '' }}">Categories</a>
                    </li>
                    <li><a href="{{ route('blogs') }}"
                            class="{{ Request::is('blogs*') || Request::is('blog/*') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('track') }}" class="{{ Request::is('track*') ? 'active' : '' }}">Track</a>
                    </li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ Request::is('contact') ? 'active' : '' }}">Contact</a></li>

                    @if (auth()->check() && auth()->user()->role_id != 1)
                        <li><a href="{{ route('order') }}">Orders</a></li>
                        <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                        <li><a href="{{ route('dashboard') }}"
                                class="{{ Request::is('dashboard') ? 'active' : '' }}">Account</a></li>
                    @endif

                    @if (auth()->check() && auth()->user()->role_id == 1)
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @endif

                    @foreach (App\Models\Page::where('position', 0)->where('status', 1)->get() as $page)
                        <li><a href="{{ route('page', ['slug' => $page->name]) }}">{{ $page->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        // Desktop-only script: scoped to this component's IDs => no conflict with mobile
        const root = document.getElementById('navCategoriesDesktop');
        if (!root) return;

        // Make buttons toggleable by click for keyboard/tablet users
        root.querySelectorAll('button.link-like').forEach(btn => {
            const parent = btn.closest('.category-item');
            const submenu = parent.querySelector('.sub-menu');

            // initial state
            btn.setAttribute('aria-expanded', 'false');
            if (submenu) submenu.setAttribute('aria-hidden', 'true');

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const isOpen = parent.classList.contains('desktop-open');

                // close other opened
                root.querySelectorAll('.category-item.desktop-open').forEach(other => {
                    if (other !== parent) {
                        other.classList.remove('desktop-open');
                        const b = other.querySelector('button.link-like');
                        const s = other.querySelector('.sub-menu');
                        if (b) b.setAttribute('aria-expanded', 'false');
                        if (s) s.setAttribute('aria-hidden', 'true');
                    }
                });

                if (isOpen) {
                    parent.classList.remove('desktop-open');
                    btn.setAttribute('aria-expanded', 'false');
                    if (submenu) submenu.setAttribute('aria-hidden', 'true');
                } else {
                    parent.classList.add('desktop-open');
                    btn.setAttribute('aria-expanded', 'true');
                    if (submenu) submenu.setAttribute('aria-hidden', 'false');
                }
            });
        });

        // close on outside click or Escape
        document.addEventListener('click', function(e) {
            if (!root.contains(e.target)) {
                root.querySelectorAll('.category-item.desktop-open').forEach(o => {
                    o.classList.remove('desktop-open');
                    const b = o.querySelector('button.link-like');
                    const s = o.querySelector('.sub-menu');
                    if (b) b.setAttribute('aria-expanded', 'false');
                    if (s) s.setAttribute('aria-hidden', 'true');
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                root.querySelectorAll('.category-item.desktop-open').forEach(o => {
                    o.classList.remove('desktop-open');
                    const b = o.querySelector('button.link-like');
                    const s = o.querySelector('.sub-menu');
                    if (b) b.setAttribute('aria-expanded', 'false');
                    if (s) s.setAttribute('aria-hidden', 'true');
                });
            }
        });
    })();
</script>

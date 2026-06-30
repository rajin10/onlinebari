{{-- resources/views/layouts/frontend/partials/mobile-sidebar.blade.php --}}
<div id="mobileSidebarComponent" aria-hidden="true">
    <style>
        /* Reset and basic styles */
        @media (min-width: 992px) { 
            #mobileSidebarComponent { 
                display: none; 
            } 
        }

        /* Clean overlay */
        #msc-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            z-index: 1500;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        #msc-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Compact panel */
        #msc-panel {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 300px;
            max-width: 85vw;
            background: #ffffff;
            z-index: 1501;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
        }
        #msc-panel.open {
            transform: translateX(0);
        }

        /* Compact header */
        #msc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            background: linear-gradient(135deg, rgb(10 126 182) 0%, rgb(34 56 90) 100%);
            border-bottom: none;
        }
        #msc-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: white;
            letter-spacing: 0.2px;
        }
        #msc-close {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 20px;
            transition: all 0.2s ease;
        }
        #msc-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }
        #msc-close:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        /* Compact content */
        #msc-content {
            padding: 16px 14px;
            overflow-y: auto;
            flex: 1;
            background: #f9fafb;
        }

        /* Compact list */
        .msc-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        /* Compact items */
        .msc-item {
            margin-bottom: 6px;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.15s ease;
        }
        .msc-item:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }
        
        .msc-item > a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            text-decoration: none;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.15s ease;
            position: relative;
        }
        .msc-item > a:hover {
            background: #f3f4f6;
            color: #111827;
        }
        
        /* Active state for regular items */
        .msc-item.active {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-color: #f85606;
        }
        .msc-item.active > a {
            color: #f85606;
            font-weight: 600;
        }
        .msc-item.active > a::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            background: #f85606;
            border-radius: 50%;
        }

        /* Icon styling */
        .msc-icon {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            opacity: 0.8;
        }
        .msc-item.active .msc-icon {
            opacity: 1;
        }

        /* Parent items */
        .msc-parent {
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            margin-bottom: 6px;
            overflow: hidden;
        }
        .msc-parent:hover {
            border-color: #d1d5db;
        }
        
        .msc-parent > .msc-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            color: #374151;
            text-align: left;
            transition: all 0.15s ease;
            gap: 10px;
        }
        .msc-parent > .msc-toggle:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .msc-parent > .msc-toggle:focus {
            outline: 2px solid #f85606;
            outline-offset: -2px;
            border-radius: 6px;
        }
        
        /* Active state for accordion parent */
        .msc-parent.open {
            background: linear-gradient(135deg, #f1f5f9 0%, #fec7c7 100%);
            border-color: #c7d2fe;
        }
        .msc-parent.open > .msc-toggle {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #f85606;
            font-weight: 600;
        }
        
        .msc-parent > .msc-toggle::after {
            content: "›";
            color: #94a3b8;
            font-size: 16px;
            transition: transform 0.25s ease;
            transform: rotate(90deg);
            margin-left: auto;
        }
        .msc-parent.open > .msc-toggle::after {
            transform: rotate(-90deg);
            color: #4f46e5;
        }

        /* Submenu */
        .msc-sub {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .msc-parent.open .msc-sub {
            max-height: 1000px;
        }

        .msc-sub a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px 10px 36px;
            text-decoration: none;
            color: #4b5563;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.15s ease;
            position: relative;
        }
        .msc-sub a:last-child {
            border-bottom: none;
        }
        .msc-sub a:hover {
            background: #f1f5f9;
            color: #111827;
        }
        .msc-sub a:focus {
            outline: 2px solid #6366f1;
            outline-offset: -2px;
            border-radius: 4px;
        }
        
        /* Active state for submenu items */
        .msc-sub a.active {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4f46e5;
            font-weight: 600;
            border-bottom-color: #c7d2fe;
        }
        .msc-sub a.active::before {
            content: '';
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            background: #4f46e5;
            border-radius: 50%;
        }

        /* Submenu icon */
        .msc-sub .msc-icon {
            font-size: 13px;
        }

        /* Make sure sub items are visible */
        .msc-sub li {
            display: block !important;
        }

        /* Custom scrollbar */
        #msc-content::-webkit-scrollbar {
            width: 4px;
        }
        #msc-content::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        #msc-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
        #msc-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Focus styles */
        *:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
            border-radius: 4px;
        }
    </style>

    <div id="msc-overlay" tabindex="-1" aria-hidden="true"></div>

    <aside id="msc-panel" role="dialog" aria-modal="true" aria-label="Mobile sidebar" tabindex="-1">
        <div id="msc-header">
            <h4>Menu</h4>
            <button id="msc-close" aria-label="Close menu">&times;</button>
        </div>

        <div id="msc-content" tabindex="0">
            @php
                use App\Models\Category;
                $categories = Category::with('subcategories')->get();
                
                // Get current URL for active state
                $currentUrl = url()->current();
                $currentPath = request()->path();
            @endphp

            <ul id="msc-list" class="msc-list" role="menu" aria-label="Mobile categories">
                <li class="msc-item {{ $currentPath === '/' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <span class="msc-icon">🏠</span>
                        <span>Home</span>
                    </a>
                </li>

                @foreach ($categories as $category)
                    @if ($category->subcategories->count())
                        @php
                            $catUrl = url('category/' . $category->slug);
                            $isActive = str_contains($currentUrl, $category->slug) || 
                                       $currentPath === 'category/' . $category->slug;
                        @endphp
                        <li class="msc-item msc-parent {{ $isActive ? 'open' : '' }}" data-slug="{{ $category->slug }}">
                            <button type="button" class="msc-toggle" aria-expanded="false">
                                <span class="msc-icon">📁</span>
                                <span>@if ($category->status == 1) {{ $category->name }} @endif</span>
                            </button>
                            <ul class="msc-sub" aria-hidden="true" style="display: none;">
                                @foreach ($category->subcategories as $sub)
                                    @php
                                        $subUrl = url('sub-category/' . $sub->slug);
                                        $isSubActive = str_contains($currentUrl, $sub->slug) || 
                                                      $currentPath === 'sub-category/' . $sub->slug;
                                    @endphp
                                    <li style="display: block;">
                                        <a href="{{ $subUrl }}" class="{{ $isSubActive ? 'active' : '' }}">
                                            <span class="msc-icon">📄</span>
                                            <span>{{ $sub->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php
                            $catUrl = url('category/' . $category->slug);
                            $isActive = str_contains($currentUrl, $category->slug) || 
                                       $currentPath === 'category/' . $category->slug;
                        @endphp
                        <li class="msc-item {{ $isActive ? 'active' : '' }}">
                            <a href="{{ $catUrl }}">
                                <span class="msc-icon">📦</span>
                                <span>@if ($category->status == 1) {{ $category->name }} @endif</span>
                            </a>
                        </li>
                    @endif
                @endforeach

                @php
                    $routes = [
                        'product' => ['icon' => '🛍️', 'name' => 'All Products'],
                        'track' => ['icon' => '📊', 'name' => 'Order Track'],
                        'contact' => ['icon' => '📞', 'name' => 'Contact Us']
                    ];
                @endphp

                @foreach($routes as $route => $data)
                    <li class="msc-item {{ request()->routeIs($route) ? 'active' : '' }}">
                        <a href="{{ route($route) }}">
                            <span class="msc-icon">{{ $data['icon'] }}</span>
                            <span>{{ $data['name'] }}</span>
                        </a>
                    </li>
                @endforeach

                @if (auth()->check() && auth()->user()->role_id != 1)
                    @php
                        $userRoutes = [
                            'order' => ['icon' => '📋', 'name' => 'Orders'],
                            'wishlist' => ['icon' => '❤️', 'name' => 'Wishlist'],
                            'dashboard' => ['icon' => '👤', 'name' => 'My Account']
                        ];
                    @endphp
                    @foreach($userRoutes as $route => $data)
                        <li class="msc-item {{ request()->routeIs($route) ? 'active' : '' }}">
                            <a href="{{ route($route) }}">
                                <span class="msc-icon">{{ $data['icon'] }}</span>
                                <span>{{ $data['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif

                @if (auth()->check() && auth()->user()->role_id == 1)
                    <li class="msc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <span class="msc-icon">⚙️</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                @foreach (App\Models\Page::where('position', 0)->where('status', 1)->get() as $page)
                    @php
                        $pageUrl = route('page', ['slug' => $page->name]);
                        $isActive = $currentUrl === $pageUrl;
                    @endphp
                    <li class="msc-item {{ $isActive ? 'active' : '' }}">
                        <a href="{{ $pageUrl }}">
                            <span class="msc-icon">📄</span>
                            <span>{{ $page->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <script>
    (function(){
        'use strict';
        try {
            var overlay = document.getElementById('msc-overlay');
            var panel = document.getElementById('msc-panel');
            var closeBtn = document.getElementById('msc-close');
            var root = document.getElementById('mobileSidebarComponent');

            if (!panel || !overlay || !root) return;

            var lastFocused = null;
            var storedScroll = 0;

            // Simple scroll lock
            function scrollbarLock(lock) {
                var html = document.documentElement;
                var body = document.body;
                if (lock) {
                    html.style.overflow = 'hidden';
                    body.style.overflow = 'hidden';
                } else {
                    html.style.overflow = '';
                    body.style.overflow = '';
                }
            }

            function openSidebar(opener) {
                lastFocused = opener || document.activeElement;
                storedScroll = window.pageYOffset || document.documentElement.scrollTop || 0;

                overlay.classList.add('open');
                overlay.setAttribute('aria-hidden','false');
                panel.classList.add('open');
                panel.setAttribute('aria-hidden','false');
                root.setAttribute('aria-hidden','false');

                scrollbarLock(true);

                // Fix body position
                document.body.style.position = 'fixed';
                document.body.style.top = (-storedScroll) + 'px';
                document.body.classList.add('msc-body-fixed');

                // Focus first element
                setTimeout(function(){
                    var first = panel.querySelector('button, a');
                    if (first) first.focus();
                }, 50);

                document.addEventListener('keydown', escClose);
            }

            function closeSidebar() {
                overlay.classList.remove('open');
                overlay.setAttribute('aria-hidden','true');
                panel.classList.remove('open');
                panel.setAttribute('aria-hidden','true');
                root.setAttribute('aria-hidden','true');

                scrollbarLock(false);

                // Restore body position
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.classList.remove('msc-body-fixed');
                window.scrollTo(0, storedScroll);

                // Close all accordions
                panel.querySelectorAll('.msc-parent.open').forEach(function(p){
                    p.classList.remove('open');
                    var sub = p.querySelector('.msc-sub');
                    if (sub) {
                        sub.style.maxHeight = '0';
                    }
                    var t = p.querySelector('.msc-toggle');
                    if (t) t.setAttribute('aria-expanded','false');
                });

                document.removeEventListener('keydown', escClose);

                // Restore focus
                if (lastFocused && lastFocused.focus) {
                    setTimeout(function() { lastFocused.focus(); }, 10);
                }
            }

            function escClose(e) { 
                if (e.key === 'Escape') closeSidebar(); 
            }

            // Fixed accordion
            function initAccordion() {
                var parents = panel.querySelectorAll('.msc-parent');
                parents.forEach(function(p){
                    var toggle = p.querySelector('.msc-toggle');
                    var sub = p.querySelector('.msc-sub');
                    
                    if (!toggle || !sub) return;
                    
                    // Make sure sub is visible when initialized
                    sub.style.display = 'block';
                    
                    // Set initial state based on class
                    var isOpen = p.classList.contains('open');
                    if (isOpen) {
                        sub.style.maxHeight = sub.scrollHeight + 'px';
                        toggle.setAttribute('aria-expanded', 'true');
                    } else {
                        sub.style.maxHeight = '0';
                        toggle.setAttribute('aria-expanded', 'false');
                    }

                    toggle.addEventListener('click', function(ev){
                        ev.preventDefault();
                        ev.stopPropagation();
                        var isOpen = p.classList.contains('open');
                        var sub = p.querySelector('.msc-sub');
                        
                        // Close other accordions
                        panel.querySelectorAll('.msc-parent.open').forEach(function(other){
                            if (other !== p) {
                                other.classList.remove('open');
                                var otherSub = other.querySelector('.msc-sub');
                                if (otherSub) {
                                    otherSub.style.maxHeight = '0';
                                }
                                var otherToggle = other.querySelector('.msc-toggle');
                                if (otherToggle) {
                                    otherToggle.setAttribute('aria-expanded','false');
                                }
                            }
                        });

                        if (isOpen) {
                            // Close this accordion
                            p.classList.remove('open');
                            if (sub) {
                                sub.style.maxHeight = '0';
                            }
                            toggle.setAttribute('aria-expanded','false');
                        } else {
                            // Open this accordion
                            p.classList.add('open');
                            if (sub) {
                                // Calculate exact height for smooth animation
                                sub.style.maxHeight = sub.scrollHeight + 'px';
                            }
                            toggle.setAttribute('aria-expanded','true');
                        }
                    });
                });
            }

            // Bind events
            overlay.addEventListener('click', closeSidebar);
            closeBtn.addEventListener('click', closeSidebar);

            function bindTriggers() {
                document.querySelectorAll('[data-open-mobile-sidebar]').forEach(function(btn){
                    btn.addEventListener('click', function(ev){
                        ev.preventDefault();
                        openSidebar(btn);
                    });
                });
            }

            // Initialize
            bindTriggers();
            setTimeout(initAccordion, 100);

            // Auto-open accordion if current page is in submenu
            function autoOpenAccordions() {
                var activeSubItems = panel.querySelectorAll('.msc-sub a.active');
                activeSubItems.forEach(function(item){
                    var parent = item.closest('.msc-parent');
                    if (parent) {
                        parent.classList.add('open');
                        var sub = parent.querySelector('.msc-sub');
                        if (sub) {
                            sub.style.maxHeight = sub.scrollHeight + 'px';
                        }
                        var toggle = parent.querySelector('.msc-toggle');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                });
            }
            setTimeout(autoOpenAccordions, 150);

            // Simple event listener for external control
            document.addEventListener('mobile-sidebar-control', function(e){
                var action = e.detail && e.detail.action;
                if (action === 'open') openSidebar();
                else if (action === 'close') closeSidebar();
                else if (action === 'toggle') {
                    if (panel.classList.contains('open')) closeSidebar();
                    else openSidebar();
                }
            });

            // Add keyboard navigation for sidebar
            panel.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    var focusable = panel.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');
                    if (focusable.length) {
                        var first = focusable[0];
                        var last = focusable[focusable.length - 1];
                        
                        if (e.shiftKey) {
                            if (document.activeElement === first) {
                                e.preventDefault();
                                last.focus();
                            }
                        } else {
                            if (document.activeElement === last) {
                                e.preventDefault();
                                first.focus();
                            }
                        }
                    }
                }
            });

        } catch (err) {
            console.warn('Mobile sidebar init failed:', err);
        }
    })();
    </script>
</div>
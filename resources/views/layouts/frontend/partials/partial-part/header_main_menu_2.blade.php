<div class="main-menu">
    <div class="container">

        <div class="back">
            <i class="fas fa-long-arrow-alt-left"></i> back
        </div>
        <div class="collpase-menu-open" style="display: none;">
            <a id="menu" class="active" href="#">MENU</a>
            {{-- <a id="cat" href="#">CATEGORIES</a> --}}
        </div>

        <div class="menu_style_2">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('product') }}" class="{{ Request::is('product*') ? 'active' : '' }}">Shop</a>
                </li>
                <li><a href="{{ route('categories_all') }}"
                        class="{{ Request::is('categories_all*') || Request::is('category/*') || Request::is('sub-category/*') || Request::is('mini-category/*') || Request::is('extra-category/*') ? 'active' : '' }}">Categories</a>
                </li>
                <li><a href="{{ route('blogs') }}"
                        class="{{ Request::is('blogs*') || Request::is('blog/*') ? 'active' : '' }}">Blog</a></li>
                <li><a href="{{ route('track') }}" class="{{ Request::is('track*') ? 'active' : '' }}">Track</a></li>
                <li><a href="{{ route('contact') }}" class="{{ Request::is('contact') ? 'active' : '' }}">Contact</a>
                </li>
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

<style>
    .menu_style_2 ul {
        display: flex;
        -ms-flex-direction: column;
        justify-content: center;
        flex-direction: row;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        width: 100%;
        position: relative;
        left: -10px;
        flex-wrap: wrap;
    }

    .menu_style_2 ul li {
        position: relative;
        padding: .7rem 0;
    }

    .menu_style_2 ul li a {
        color: var(--MAIN_MENU_ul_li_color) !important;
        font-weight: 600;
        padding: 0 18px 0 8px;
    }

    .menu_style_2 ul li a:hover {
        color: var(--optional_color) !important;
        background: transparent;
    }


    .menu_style_2 ul li ul {
        display: none;
        position: absolute;
        width: max-content;
        top: 2.8rem;
        left: 10px;
        z-index: 99;
        padding: 0 0;
        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1), 0 10px 15px rgba(0, 0, 0, 0.1), 0 -3px 0 0 #ef4a23;

        border: 0 !important;
    }

    .menu_style_2 ul li ul li {
        all: unset;
        padding: 0 !important;
        margin: 0 !important;
    }

    .menu_style_2 ul li ul li a {
        background: var(--optional_bg_color_text) !important;
        color: var(--optional_color) !important;
    }

    .menu_style_2 ul li ul li:hover a {
        background: var(--optional_color) !important;
        color: var(--optional_bg_color_text) !important;
    }
</style>

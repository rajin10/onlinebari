<div class="main-menu">
    <div class="container">
        <div class="back">
            <i class="fas fa-long-arrow-alt-left"></i> back
        </div>
        <div class="collpase-menu-open" style="display: none;">
            <a id="menu" class="active" href="#">MENU</a>
        </div>

        <div class="nav-bar">
            <div class="nav-menus">
                <ul>
                    <li><a href="{{ route('home') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('product') }}" class="{{ Request::is('product*') ? 'active' : '' }}">Shop</a>
                    </li>
                    <li><a href="{{ route('categories_all') }}"
                            class="{{ Request::is('categories_all*') || Request::is('category/*') || Request::is('sub-category/*') || Request::is('mini-category/*') || Request::is('extra-category/*') ? 'active' : '' }}">Categories</a>
                    </li>
                    <li><a href="{{ route('blogs') }}"
                            class="{{ Request::is('blogs*') || Request::is('blog/*') ? 'active' : '' }}">Blog</a>
                    </li>
                    <li><a href="{{ route('track') }}" class="{{ Request::is('track*') ? 'active' : '' }}">Track</a>
                    </li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ Request::is('contact') ? 'active' : '' }}">Contact</a></li>
                    {{-- <li class="submenu" style="position:relative !important"><a href="{{route('blogs')}}">Updates</a></li> --}}
                    {{-- <li><a href="{{route('track')}}" class="{{Request::is('track*') ? 'active':''}}">Order Track</a></li> --}}
                    {{-- <li><a href="{{route('category')}}" class="{{Request::is('category*') ? 'active':''}}">All Category</a></li> --}}
                    {{-- <li><a href="{{route('sheba')}}" class="{{Request::is('sheba') ? 'active':''}}"><i class="icofont icofont-live-support"></i>Sheba</a></li> --}}
                    {{-- <li><a href="{{route('service')}}" class="{{Request::is('service') ? 'active':''}}"><i class="icofont icofont-live-support"></i>Sheba</a></li> --}}
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    header {
        border-bottom: none !important;

    }

    .main-menu {
        background: #0f6ed3 !important;
    }
</style>

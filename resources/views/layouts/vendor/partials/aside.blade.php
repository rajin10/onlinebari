@php
    $navActive = fn ($patterns) => request()->is($patterns) ? 'bg-accent text-accent-fg' : 'text-slate-700 hover:bg-accent hover:text-accent-fg';
@endphp

<aside class="dash-sidebar fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 flex-col overflow-y-auto border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       x-data="{ open: { products: {{ request()->is('vendor/product*') ? 'true' : 'false' }}, profile: {{ request()->is('vendor/profile*') ? 'true' : 'false' }} } }">

    <div class="border-b border-slate-200 px-4 py-4">
        <img src="/uploads/setting/{{ setting('logo') }}" alt="Logo" class="max-h-10">
    </div>

    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-3">
        <img src="{{ Auth::user()->avatar != 'default.png' ? '/uploads/member/'.Auth::user()->avatar : '/default/user.jpg' }}"
             class="h-10 w-10 rounded-full object-cover" alt="User">
        <span class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</span>
    </div>

    <nav class="flex flex-1 flex-col gap-1 p-2 text-sm">
        <a href="{{ routeHelper('dashboard') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 {{ $navActive('vendor/dashboard') }}">
            <i class="bx bxs-dashboard text-lg"></i> Dashboard
        </a>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
            <i class="bx bx-user text-lg"></i> Customer Panel
        </a>

        <div>
            <button type="button" @click="open.products = !open.products"
                    class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
                <i class="bx bx-package text-lg"></i> Products
                <i class="bx bx-chevron-down ml-auto transition-transform" :class="open.products && 'rotate-180'"></i>
            </button>
            <ul x-show="open.products" x-cloak class="ml-4 mt-1 flex flex-col gap-1 border-l border-slate-200 pl-2">
                <li><a href="{{ routeHelper('product/create') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/create') }}"><i class="bx bx-plus-circle"></i> Add</a></li>
                <li><a href="{{ routeHelper('product') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product') }}"><i class="bx bx-list-ul"></i> List</a></li>
                <li><a href="{{ route('vendor.low.product') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/low/product') }}"><i class="bx bx-error"></i> Low qnty</a></li>
                <li><a href="{{ routeHelper('product/active') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/active') }}"><i class="bx bx-like"></i> Active</a></li>
                <li><a href="{{ routeHelper('product/disable') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/product/disable') }}"><i class="bx bx-dislike"></i> Disable</a></li>
            </ul>
        </div>

        <div>
            <button type="button" @click="open.profile = !open.profile"
                    class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
                <i class="bx bx-user-circle text-lg"></i> Profile
                <i class="bx bx-chevron-down ml-auto transition-transform" :class="open.profile && 'rotate-180'"></i>
            </button>
            <ul x-show="open.profile" x-cloak class="ml-4 mt-1 flex flex-col gap-1 border-l border-slate-200 pl-2">
                <li><a href="{{ routeHelper('profile') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/profile') }}"><i class="bx bx-user"></i> My Profile</a></li>
                <li><a href="{{ routeHelper('profile/change-password') }}" class="flex items-center gap-2 rounded-md px-3 py-1.5 {{ $navActive('vendor/profile/change-password') }}"><i class="bx bx-key"></i> Change Password</a></li>
            </ul>
        </div>

        <a href="{{ route('vendor.withdraw') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 {{ $navActive('vendor/withdraw*') }}">
            <i class="bx bx-money text-lg"></i> Withdraw
        </a>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="mt-auto flex items-center gap-3 rounded-md px-3 py-2 text-slate-700 hover:bg-accent hover:text-accent-fg">
            <i class="bx bx-power-off text-lg"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>

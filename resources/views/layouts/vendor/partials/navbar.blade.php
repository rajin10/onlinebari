<header class="flex h-14 items-center gap-4 border-b border-slate-200 bg-white px-4">
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="text-slate-600 hover:text-slate-900 lg:hidden" aria-label="Toggle sidebar">
        <i class="bx bx-menu text-2xl"></i>
    </button>

    <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
        <i class="bx bx-globe"></i> Visit Site
    </a>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();"
       class="ml-auto inline-flex items-center gap-2 rounded-md bg-accent px-3 py-1.5 text-sm font-medium text-accent-fg">
        <i class="bx bx-power-off"></i> Logout
    </a>
    <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
</header>

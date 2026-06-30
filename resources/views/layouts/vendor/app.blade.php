<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/jpg" href="/uploads/setting/{{ setting('favicon') }}"/>
    <title>@yield('title')</title>

    @include('layouts.global')

    <!-- Source Sans Pro (dashboard font) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- boxicons (sidebar icons) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Font Awesome (stat-tile + content icons) -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">

    @notifyCss
    @stack('css')

    <!-- adminlte.min.css retained ONLY as the Bootstrap 4 base for the kept JS widgets
         (DataTables/select2/summernote bootstrap4 themes). The vendor chrome is now 100%
         Tailwind — no AdminLTE chrome classes are used. Removing this would break the widgets;
         fully dropping Bootstrap requires replacing those JS widgets (out of scope). -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">

    <!-- Tailwind LAST so it wins on the rebuilt pieces. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dash-shell min-h-screen bg-slate-100 font-dash text-slate-700"
      x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        @include('layouts.vendor.partials.aside')

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
             class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            @include('layouts.vendor.partials.navbar')
            <main class="flex-1 p-4">
                @yield('content')
            </main>
            <footer class="border-t border-slate-200 bg-white py-2 text-center text-xs text-slate-500">
                Laravel Ecommerce system by: Finva Soft Limited
            </footer>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap bundle (still used by un-migrated views' JS widgets) -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <x-notify::notify />
    @notifyJs
    @stack('js')

    <script>
        setInterval(function () { $('.notify').hide(); }, 2000);
        $(document).on('click', '#deleteData', function (e) {
            let id = $(this).data('id');
            e.preventDefault();
            if (confirm('Are you sure delete this data!!')) {
                document.getElementById('delete-data-form-' + id).submit();
            }
        });
    </script>
</body>
</html>

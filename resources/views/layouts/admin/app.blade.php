<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/jpg" href="/uploads/setting/{{ setting('favicon') }}" />

    <title>@yield('title')</title>

    {{-- TODO: We will use that later --}}
    {{-- @include('layouts.global') --}}

    <!-- Google Font: Source Sans Pro -->

    {{-- Dashboard CSS --}}
    <link rel="stylesheet" href="{{ asset('dashboard-assets/style.css') }}">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Font Awesome (self-hosted) — admin tables/buttons use `fas`/`fab` icon classes -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">


    @notifyCss

    @stack('css')

    <!-- adminlte.css retained as the Bootstrap base for the JS widgets; the admin chrome is
         the dashboard-assets sidebar + a Tailwind shell. -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.css">

    <!-- Tailwind LAST so its utilities win on the (being-)converted content. -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

</head>

<body class="">
    <!-- Site wrapper -->
    <div class="flex w-screen h-screen overflow-hidden">

        <!-- Navbar -->
        {{-- @include('layouts.admin.e-commerce.partials.navbar') --}}
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.admin.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="flex-1 overflow-y-auto p-4">

            @yield('content')

            {{-- <p style="text-align:center;margin: 0;padding: 5px 0px;">Developed by: <a target="_blank"
                    href="https://reliableuksolutions.com">Wedevs</a></p> --}}
        </div>

    </div>


    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>


    <x-notify::notify />
    @notifyJs

    @stack('js')
    <script>
        setInterval(function() {
            $('.notify').hide();
        }, 2000);
        $(document).on('click', '#deleteData', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            let conf = confirm('Are you sure delete this data!!');
            if (conf) {

                document.getElementById('delete-data-form-' + id).submit();
            }

        })
    </script>
    {{-- <script src="/assets/dist/js/demo.js"></script> --}}

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const path = window.location.pathname;

            document.querySelectorAll('.sidebar li').forEach(function(li) {
                li.classList.remove('active');

                const onclick = li.getAttribute('onclick');
                if (!onclick) return;

                const match = onclick.match(/admin\/[^'")]+/);
                if (!match) return;

                const itemPath = '/' + match[0];

                if (path === itemPath || path.startsWith(itemPath + '/')) {
                    li.classList.add('active');
                }
            });
        });
    </script> --}}

</body>

</html>

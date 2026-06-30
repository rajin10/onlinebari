<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    @notifyCss
    {{-- Tailwind loaded BEFORE legacy bundles → legacy wins during the layer-on-top phase. --}}
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="/assets/frontend/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/frontend/css/style.css">
    <link rel="stylesheet" href="/assets/frontend/css/slick.css">
    <link rel="stylesheet" href="/assets/frontend/css/icofont.css">
</head>
<body>
      <br>
      <br>
      <br>
    <!--================about  Area start=================-->
    <div id="contact-page">
        <div class="container">
            @yield('content')
        </div>
    </div>
<!--================about  Area start=================-->
    <!--================footer  Area start=================-->

    <footer class="out-footer">
        <div class="container">
            <div class="row">
                <div class="widget col-md-12">
                    <ul>
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{url('product')}}">Shop</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-center">{{setting('copy_right_text')}}</p>
        </div>
    </footer>
    <!--================footer  Area end=================-->

    <x-notify::notify />
    @notifyJs
    <script src="/assets/frontend/js/jquery.js"></script>
    <script src="/assets/frontend/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/js/slick.js"></script>
    <script src="/assets/frontend/js/main.js"></script>
</body>
</html>
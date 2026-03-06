<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed ">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>{{ $setting->siteName($pageTitle ?? '') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoFavicon') . '/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">

        <!-- Css -->
        <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/theme.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/line-awesome.css') }}">
    
<link rel="stylesheet" href="sweetalert2.min.css">
  

        @stack('page-style-lib')
        @stack('page-style')
    </head>

    <body>
        @yield('content')
         
        <script src="{{ asset('assets/admin/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/admin/js/config.js') }}"></script>
        <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset("assets/admin/js/page/menu.js") }}"></script>
        <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/admin/js/scrollbar.js') }}"></script>

        @include('partials.toasts')
        @stack('page-script-lib')
       
        @stack('page-script')

        {{-- script --}}
  <script src="{{ asset('assets/admin/js/main.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       

    </body>
</html>

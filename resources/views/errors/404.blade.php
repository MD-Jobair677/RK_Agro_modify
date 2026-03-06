<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed ">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title>{{ $setting->siteName($pageTitle ?? '') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoFavicon').'/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">

        <!-- Css -->
        <link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/theme.css')}}">

        <style>
            .misc-wrapper {
                display:flex;
                flex-direction:column;
                justify-content:center;
                align-items:center;
                min-height:calc(100vh - (1.625rem * 2));
                text-align:center;
            }
        </style>
    </head>

    <body>
        <div class="container-xxl container-p-y">
            <div class="misc-wrapper">
              <h2 class="mb-2 mx-2">404, Page Not Found :(</h2>
              <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
              <a href="{{ route('home') }}" class="btn btn-primary">Back to home</a>
              <div class="mt-3">
                <img src="{{ asset('assets/admin/images/error.png') }}" alt="page-misc-error-light" width="500" class="img-fluid">
              </div>
            </div>
          </div>
    <body>
</html>
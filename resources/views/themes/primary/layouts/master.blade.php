<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $setting->siteName(__($pageTitle)) }}</title>
        @include('partials.seo')

        <link rel="stylesheet" href="{{ asset('assets/universal/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/universal/css/line-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/main.css') }}">

        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/color.php?color1=' . $setting->first_color . '&color2=' . $setting->second_color) }}">
        @stack('page-style-lib')
        @stack('page-style')
    </head>

    <body>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_light.png') }}" alt="@lang('Image')">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('Toggle navigation')">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
    
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">@lang('contact')</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.login') }}">@lang('login')</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('user.register') }}">@lang('register')</a>
                            </li>
                            @endguest
                            @auth
                            <li class="nav-item">
                                <a class="nav-link"
                                href="{{ route('user.home') }}">@lang('Dashboard')</a>
                            </li>
                           
                           <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('Deposit')
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                href="{{ route('user.deposit') }}">@lang('Deposit Money')</a>
                                <a class="dropdown-item"
                                href="{{ route('user.deposit.history') }}">@lang('Deposit
                                        Log')</a>
                                </div>
                            </li>
    
                             <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('Withdraw')
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item"
                                        href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a>
                                    <a class="dropdown-item"
                                        href="{{ route('user.withdraw.history') }}">@lang('Withdraw
                                        Log')</a>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.transactions') }}">@lang('Transactions')</a>
                            </li>
    
    
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ auth()->user()->fullname }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.change.password') }}">
                                        @lang('Change Password')
                                    </a>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        @lang('Profile Setting')
                                    </a>
                                    <a class="dropdown-item" href="{{ route('user.twofactor.form') }}">
                                        @lang('2FA Security')
                                    </a>
    
    
                                    <a class="dropdown-item" href="{{ route('user.logout') }}">
                                        @lang('Logout')
                                    </a>
    
                                </div>
                            </li>
                        @endauth
    
                    </ul>
                </div>
            </div>
        </nav>
    
        <div class="page-wrapper">
            @yield('content')
        </div>
    

        <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>



<h1>hello</h1>

        <script src="{{ asset($activeThemeTrue . 'js/main.js') }}"></script>

        @include('partials.plugins')
        @include('partials.toasts')
        @stack('page-script-lib')
        @stack('page-script')

        <script>
            (function($) {
                "use strict";

                $(".langSel").on("change", function() {
                    window.location.href = "{{ route('home') }}/change/" + $(this).val();
                });

                $('.policy').on('click', function() {
                    $.get('{{ route('cookie.accept') }}', function(response) {
                        $('.cookies-card').addClass('d-none');
                    });
                });

                setTimeout(function() {
                    $('.cookies-card').removeClass('hide');
                }, 2000);

            })(jQuery);
        </script>

    </body>
</html>

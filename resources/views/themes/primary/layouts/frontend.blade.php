{{-- <!DOCTYPE html>
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

        @yield('content')

        @php
            $cookie = App\Models\SiteData::where('data_key', 'cookie.data')->first();
        @endphp

        @if ($cookie->data_info->status == ManageStatus::ACTIVE && !\Cookie::get('gdpr_cookie'))
            <!-- cookies dark version start -->
            <div class="cookies-card text-center hide">
                <div class="cookies-card__icon">
                    <img src="{{ getImage('assets/images/cookie.png') }}" alt="cookies">
                </div>

                <p class="mt-4 cookies-card__content">{{ $cookie->data_info->short_details }}</p>

                <div class="cookies-card__btn mt-4">
                    <button type="button" class="btn btn--base px-5 policy">@lang('Allow')</button>
                    <a href="{{ route('cookie.policy') }}" target="_blank" type="button" class="text--base px-5 pt-3">@lang('Learn More')</a>
                </div>
            </div>
            <!-- cookies dark version end -->
        @endif

        <script src="{{ asset('assets/universal/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/universal/js/bootstrap.js') }}"></script>
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
</html> --}}

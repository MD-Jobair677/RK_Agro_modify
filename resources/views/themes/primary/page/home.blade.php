@extends($activeTheme . 'layouts.frontend')
@section('content')
@php $languages = App\Models\Language::active()->get() @endphp
<div class="container">
    <div class="document-header d-flex flex-wrap justify-content-between align-items-center mb-2">
        <div class="logo"><a href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoFavicon') . '/logo_light.png') }}" class="w-50" alt="@lang('Image')"></a></div>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link">@lang('Home')</a>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('contact')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.login.form') }}">@lang('login')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.register') }}">@lang('register')</a>
                    </li>
                    @endguest

                    @auth

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{
                            auth()->user()->fullname }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('user.logout') }}">
                                @lang('Logout')
                            </a>
                        </div>
                    </li>
                    @endauth

                   <select class="langSel form-control">
                        <option value="">@lang('Select One')</option>
                        @foreach($languages as $item)
                        <option value="{{$item->code}}" @if(session('lang')==$item->code) selected @endif>{{
                            __($item->name) }}</option>
                        @endforeach
                    </select> 
                </ul>
            </div>
        </nav>
    </div>

    <div class="document-wrapper">
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="document-item d-flex flex-wrap">
                    <div class="document-item__icon">
                        <i class="lab la-readme"></i>
                    </div>
                    <div class="document-item__content">
                        <h4 class="title"><a href="#0" class="text-underline">Section Manager</a></h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta incidunt quod ipsa neque
                            consequatur aspernatur earum quos est, totam cumque!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="document-item d-flex flex-wrap">
                    <div class="document-item__icon">
                        <i class="lab la-readme"></i>
                    </div>
                    <div class="document-item__content">
                        <h4 class="title"><a href="#0" class="text-underline">Payment Gateway</a></h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta incidunt quod ipsa neque
                            consequatur aspernatur earum quos est, totam cumque!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="document-item d-flex flex-wrap">
                    <div class="document-item__icon">
                        <i class="lab la-readme"></i>
                    </div>
                    <div class="document-item__content">
                        <h4 class="title"><a href="#0" class="text-underline">Smart Code</a></h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta incidunt quod ipsa neque
                            consequatur aspernatur earum quos est, totam cumque!</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="document-item d-flex flex-wrap">
                    <div class="document-item__icon">
                        <i class="lab la-readme"></i>
                    </div>
                    <div class="document-item__content">
                        <h4 class="title"><a href="#0" class="text-underline">Smart UI/UX</a></h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta incidunt quod ipsa neque
                            consequatur aspernatur earum quos est, totam cumque!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="document-footer d-flex flex-wrap justify-content-between align-items-center mt-4">
        <ul class="d-flex flex-wrap share-links">
            <li><a href="#" target="_blank"><i class="las la-globe"></i> @lang('w3Hunt')</a></li>
        </ul>
    </div>
</div>
@endsection

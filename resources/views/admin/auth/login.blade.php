@extends('admin.layouts.app')
@section('content')
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- login card -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{route('home')}}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                              <img src="{{ getImage(getFilePath('logoFavicon').'/logo_dark.png') }}" alt="@lang('image')">
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">@lang('Welcome to') {{__($setting->site_name)}}!</h4>
                    <p class="mb-4">@lang('Please sign-in to your account and start the adventure')</p>

                    <form class="mb-3" action="{{ route('admin.login') }}" method="POST">
                            @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">@lang('Email or Username')</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="@lang('Enter your email or username')" autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">@lang('Password')</label>
                                <a href="{{ route('admin.password.request.form') }}">
                                    <small>@lang('Forgot Password?')</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="@lang('Password')"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                                <label class="form-check-label" for="remember"> @lang('Remember Me') </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary d-grid w-100">@lang('Sign in')</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /login -->
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/page/auth.css')}}">
@endpush

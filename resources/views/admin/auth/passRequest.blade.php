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
                    <h4 class="mb-2">@lang('Forgot Password?')</h4>
                    <p class="mb-4">@lang('Enter your email and we\'ll send you instructions to reset your password')</p>
                    <form class="mb-3 verify-gcaptcha" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">@lang('Email')</label>
                            <input type="email" class="form-control" name="email" placeholder="@lang('Enter your email')" required autofocus>
                        </div>
                        <div class="mb-3">
                            <x-captcha />
                        </div>
                        <button class="btn btn-primary d-grid w-100" type="submit">@lang('Send')</button>
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

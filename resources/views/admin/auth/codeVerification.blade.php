@extends('admin.layouts.app')

@section('content')
    {{-- <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('assets/admin/images/codeVerify.png') }}" class="img-fluid" alt="Login image" width="700">
                </div>
            </div>

            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <div class="app-brand mb-3 justify-content-center">
                        <a href="{{ route('home') }}" target="_blank" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo"><img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="logo"></span>
                        </a>
                    </div>
                    <div class="text-center">
                        <h4 class="mb-2">
                            @lang('Code Verification')
                            <img src="{{ asset('assets/admin/images/key.gif') }}" alt="emoji" class="animated-emoji">
                        </h4>
                        <p class="mb-4">@lang('Please check your email') <br> @lang('Get the 6 digits verification code')</p>
                    </div>

                    <form class="verification-code-form" action="" method="POST">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">
                        @include('partials.verificationCode')

                        <button class="btn btn-primary d-grid w-100" type="submit">@lang('Verify')</button>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.password.request.form') }}">
                                    <small>@lang('Send again?')</small>
                                </a>
                                <a href="{{ route('admin.login') }}" class="d-flex align-items-center justify-content-center">
                                    <i class="las la-angle-double-left"></i> @lang('Back to login')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Forgot Password -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{route('home')}}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                              <img src="{{ getImage(getFilePath('logoFavicon') . '/logo_dark.png') }}" alt="@lang('image')">
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">@lang('Verification')</h4>
                    <p class="mb-4">@lang('Please enter the verification code')</p>

                    <form class="verification-code-form" action="" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="mb-3">
                            @include('partials.verificationCode')
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">@lang('Send Code')</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.password.request.form') }}" class="d-flex align-items-center justify-content-center">
                          <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                          @lang('Try to send again')
                        </a>
                      </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/page/auth.css') }}">
@endpush

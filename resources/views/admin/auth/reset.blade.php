@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password -->
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
                        <h4 class="mb-4">@lang('Set New Password')</h4>
                    
                        <form class="mb-3" action="{{ route('admin.password.reset') }}" method="POST">
                            @csrf
    
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="code" value="{{ $verCode }}">
    
                            <div class="mb-3">
                                <label class="form-label">@lang('New Password')</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" placeholder="@lang('New Password')" required autofocus>
                                    <span class="input-group-text cursor-pointer"><i class="las la-eye-slash"></i></span>
                                </div>
                            </div>
    
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="@lang('Confirm Password')" required>
                                    <span class="input-group-text cursor-pointer"><i class="las la-eye-slash"></i></span>
                                </div>
                            </div>
    
                            <button class="btn btn-primary d-grid w-100" type="submit">@lang('Reset')</button>
                        </form>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/page/auth.css')}}">
@endpush

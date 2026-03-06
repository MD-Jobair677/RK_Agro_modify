@extends('admin.layouts.master')

@section('master')
    <div class="row">
        @include('admin.profile.basicInfo')

        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.profile') }}"><i class="las la-user-circle me-1"></i>@lang('Profile')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.password') }}"><i class="las la-key me-1"></i>@lang('Password')</a>
                </li>
            </ul>

            <div class="card mb-4">
                <h5 class="card-header">@lang('Change Password')</h5>
                <form class="card-body" action="" method="POST">
                    @csrf

                    @if ($setting->strong_pass)
                        <div class="alert alert-warning mt-0" role="alert">
                            <h6 class="alert-heading mb-1">@lang('Minimum 1 small letter is required')</h6>
                            <h6 class="alert-heading mb-1">@lang('Minimum 1 capital letter is required')</h6>
                            <h6 class="alert-heading mb-1">@lang('Minimum 1 number is required')</h6>
                            <h6 class="alert-heading mb-1">@lang('Minimum 1 special character is required')</h6>
                            <h6 class="alert-heading mb-1">@lang('Minimum 6 character is required')</h6>
                        </div>
                    @endif

                    <div class="row mb-3 form-password-toggle">
                        <label class="col-sm-3 col-form-label required">@lang('Current Password')</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="password" class="form-control" name="current_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autofocus>
                                <span class="input-group-text cursor-pointer"><i class="las la-eye-slash"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 form-password-toggle">
                        <label class="col-sm-3 col-form-label required">@lang('New Password')</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autofocus>
                                <span class="input-group-text cursor-pointer"><i class="las la-eye-slash"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 form-password-toggle">
                        <label class="col-sm-3 col-form-label required">@lang('Confirm Password')</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                <span class="input-group-text cursor-pointer"><i class="las la-eye-slash"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Submit')</button>
                            <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

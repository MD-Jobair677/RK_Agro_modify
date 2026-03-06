@extends('admin.layouts.master')

@section('master')
    <div class="row">
        @include('admin.profile.basicInfo')

        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.profile') }}"><i class="las la-user-circle me-1"></i>@lang('Profile')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.password') }}"><i class="las la-key me-1"></i>@lang('Password')</a>
                </li>
            </ul>

            <div class="card mb-4">
                <h5 class="card-header">@lang('Details')</h5>
                <div>
                    <form class="card-body" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Name')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Username')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" value="{{ $admin->username }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Email')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="email" value="{{ $admin->email }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Contact')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="contact" value="{{ $admin->contact }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Address')</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="address" rows="5" required>{{ $admin->address }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">@lang('Image')</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="image">
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
    </div>
@endsection

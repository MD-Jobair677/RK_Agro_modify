@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.admin.store') }}"method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                    style="background-image: url({{ getImage(getFilePath('adminProfile')) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="las la-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                    id="profilePicUpload1" accept=".png">
                                                <label for="profilePicUpload1" class="btn btn-primary upload-btn"
                                                    title="@lang('Light Logo')"><i class="las la-upload"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Name')</label>
                                                <input type="text" class="form-control" name="name" value="{{old('name')}}"
                                                    placeholder="@lang('Enter your name')" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Email')</label>
                                                <input type="email" class="form-control" name="email" value="{{old('email')}}"
                                                    placeholder="@lang('Enter your email')" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('UserName')</label>
                                                <input type="text" class="form-control" name="username" value="{{old('username')}}"
                                                    placeholder="@lang('Enter your username')" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label required">@lang('Password')</label>
                                        <input type="password" class="form-control" name="password" 
                                            placeholder="@lang('Enter your password')" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label required">@lang('Confirm Password')</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="@lang('Enter your contact number')" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label required">@lang('Contact Number')</label>
                                        <input type="number" class="form-control" name="contact" value="{{old('contact')}}"
                                            placeholder="@lang('Enter your contact number')" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class=" col-form-label required">@lang('Address')</label>
                                        <textarea type="text" class="form-control" rows="5" cols="5" name="address"
                                        value="{{old('address')}}"
                                            placeholder="@lang('Enter your address')" required></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 text-end">
                                    <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Save')</button>
                                    <a href=""></a>
                                    <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{route('admin.admin.index')}}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
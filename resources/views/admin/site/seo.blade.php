@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.site.sections.content', 'seo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="data">
                    <input type="hidden" name="seo_image" value="1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('seo') . '/' . @$seo->data_info->image, getFileSize('seo')) }})">
                                            <button type="button" class="remove-image"><i class="las la-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit">
                                        <input type="file" class="profilePicUpload" name="image_input" id="profilePicUpload1" accept=".png, .jpg, .jpeg" hidden>
                                        <label for="profilePicUpload1" class="btn btn-primary upload-btn" title="@lang('Seo image')"><i class="las la-upload"></i></label>
                                        <p class="pt-2">@lang('Supported files'): <mark>@lang('jpeg'), @lang('jpg'), @lang('png').</mark> @lang('Image size') <mark>{{ getFileSize('seo') }}@lang('px').</mark></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Meta Keywords')</label>
                                <div class="col-sm-9 select2-design position-relative">
                                    <select class="select2 form-select" name="keywords[]" data-allow-clear="true" multiple="multiple" required>
                                        @if (@$seo->data_info->keywords)
                                            @foreach ($seo->data_info->keywords as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Social Title')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="social_title" value="{{ @$seo->data_info->social_title }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Meta Description')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="description" rows="3" required>{{ @$seo->data_info->description }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Social Description')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="social_description" rows="3" required>{{ @$seo->data_info->social_description }}</textarea>
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Submit')</button>
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

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.js') }}"></script>
@endpush

@push('page-script')
    <script>
        "use strict";

        (function($) {
            $('.select2').select2({
                dropdownParent: '.select2-design',
                tags: true,
                tokenSeparators: ','
            });
        })(jQuery);
    </script>
@endpush

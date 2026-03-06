@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Short Details')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="short_details" rows="3" required>{{ @$cookie->data_info->short_details }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">@lang('Details')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control nicEdit" rows="8" name="details">{{ @$cookie->data_info->details }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Status')</label>
                                <div class="col-sm-9">
                                    <label class="switch me-0">
                                        <input type="checkbox" class="switch-input" name="status" @if(@$cookie->data_info->status) checked @endif>
                                        @include('admin.partials.switcher')
                                    </label>
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

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/nicEdit.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);

                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                });
            });
        })(jQuery);
    </script>
@endpush

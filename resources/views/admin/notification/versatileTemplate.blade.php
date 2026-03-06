@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Short Code')</th>
                                <th>@lang('Description')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>@{{fullname}}</td>
                                <td>@lang('Full Name of User')</td>
                            </tr>
                            <tr>
                                <td>@{{username}}</td>
                                <td>@lang('Username of User')</td>
                            </tr>
                            <tr>
                                <td>@{{message}}</td>
                                <td>@lang('Message')</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xxl">
            <div class="card mb-4">
                <h5 class="card-header">@lang('Universal Short Codes')</h5>
            </div>

            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Short Code')</th>
                                <th>@lang('Description')</th>
                            </tr>
                        </thead>

                        <tbody class="table-border-bottom-0">
                            @foreach($setting->universal_shortcodes as $shortCode => $codeDetails)
                                <tr>
                                    <td>@{{@php echo $shortCode @endphp}}</td>
                                    <td>{{ __($codeDetails) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xxl">
            <div class="card mb-4">
                <h5 class="card-header">@lang('Basic Configuration')</h5>
                <hr class="mt-0">
                <form class="card-body" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Email Sender')</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email_from" value="{{ $setting->email_from }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('SMS Sender')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sms_from" value="{{ $setting->sms_from }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Email Body')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control nicEdit" rows="8" name="email_template">{{ $setting->email_template }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('SMS Body')</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="sms_body" rows="3" required>{{ $setting->sms_body }}</textarea>
                                </div>
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

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/nicEdit.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            bkLib.onDomLoaded(function() {
                $( ".nicEdit" ).each(function( index ) {
                    $(this).attr("id","nicEditor"+index);
                    new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
                });
            });
        })(jQuery);
    </script>
@endpush


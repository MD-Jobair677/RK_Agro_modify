@extends('admin.layouts.master')

@section('master')
    <div class="row mt-4">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required">@lang('SMS Delivery Methods')</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="sms_method" required>
                                        <option value="nexmo" @selected(@$setting->sms_config->name == 'nexmo')>@lang('Nexmo')</option>
                                        <option value="twilio" @selected(@$setting->sms_config->name == 'twilio')>@lang('Twilio')</option>
                                        <option value="custom" @selected(@$setting->sms_config->name == 'custom')>@lang('Custom API')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="nexmo">
                                <h5 class="card-header border-bottom mb-4">@lang('Nexmo Config')</h5>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('API Key')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nexmo_api_key" value="{{ @$setting->sms_config->nexmo->api_key }}" placeholder="@lang('Nexmo app key')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('API Secret')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="nexmo_api_secret" value="{{ @$setting->sms_config->nexmo->api_secret }}" placeholder="@lang('Nexmo api secret key')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="twilio">
                                <h5 class="card-header border-bottom mb-4">@lang('Twilio Config')</h5>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Account SID')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="account_sid" value="{{ @$setting->sms_config->twilio->account_sid }}" placeholder="@lang('Account SID')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Auth Token')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="auth_token" value="{{ @$setting->sms_config->twilio->auth_token }}" placeholder="@lang('Auth token')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('From Number')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="from" value="{{ @$setting->sms_config->twilio->from }}" placeholder="@lang('From number')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="custom">
                                <h5 class="card-header border-bottom mb-4">@lang('Custom API Config')</h5>
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
                                                <td>@{{message}}</td>
                                                <td>@lang('Message')</td>
                                            </tr>
                                            <tr>
                                                <td>@{{number}}</td>
                                                <td>@lang('Number')</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">@lang('API URL')</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <select class="form-select form-control method-select" name="custom_api_method" required>
                                                        <option value="get">@lang('GET')</option>
                                                        <option value="post">@lang('POST')</option>
                                                    </select>
                                                </span>
                                                <input type="text" class="form-control" name="custom_api_url" value="{{ @$setting->sms_config->custom->url }}" placeholder="@lang('API URL')">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="card h-100 border">
                                                <div class="">
                                                    <div class="card-header py-3 d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom">
                                                        <h5 class="mb-0">@lang('Headers')</h5>
                                                        <button type="button" class="btn btn-label-primary addHeader">
                                                            <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row headerFields mb-3 mt-4">
                                                        @for($i = 0; $i < count($setting->sms_config->custom->headers->name); $i++)
                                                            <div class="col-sm-12 mb-3">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="custom_header_name[]" value="{{ @$setting->sms_config->custom->headers->name[$i] }}" placeholder="@lang('Headers Name')" required>
                                                                    <input type="text" class="form-control" name="custom_header_value[]" value="{{ @$setting->sms_config->custom->headers->value[$i] }}" placeholder="@lang('Headers Value')" required>
                                                                    <span class="input-group-text cursor-pointer cursor-pointer btn-label-danger removeHeader"><i class="tf-icons las la-times-circle"></i></span>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card h-100 border">
                                                <div class="card-header py-3 d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom">
                                                    <h5 class="mb-0">@lang('Body')</h5>
                                                    <button type="button" class="btn btn-label-primary addBody">
                                                        <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
                                                    </button>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row bodyFields mb-3 mt-4">
                                                        @for($i = 0; $i < count($setting->sms_config->custom->body->name); $i++)
                                                            <div class="col-sm-12 mb-3">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="custom_body_name[]" value="{{ @$setting->sms_config->custom->body->name[$i] }}" placeholder="@lang('Body Name')" required>
                                                                    <input type="text" class="form-control" name="custom_body_value[]" value="{{ @$setting->sms_config->custom->body->value[$i] }}" placeholder="@lang('Body Value')" required>
                                                                    <span class="input-group-text cursor-pointer cursor-pointer btn-label-danger removeBody"><i class="tf-icons las la-times-circle"></i></span>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    {{-- Test SMS Modal --}}
    <div class="modal fade" id="testSMSModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Test Mail Send')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.notification.sms.test') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Sent to')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="mobile" placeholder="@lang('Mobile number')" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <button type="button" class="btn btn-label-primary" data-bs-target="#testSMSModal" data-bs-toggle="modal">
        <span class="tf-icons las la-sms me-1"></span> @lang('Test SMS')
    </button>
@endpush

@push('page-style')
    <style>
        .method-select{
            padding: 0px 30px;
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('select[name=custom_api_method]').val('{{ @$setting->sms_config->custom->method }}');
            var method = '{{ $setting->sms_config->name }}';

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

            $('.addHeader').click(function() {
                let html = `<div class="col-sm-12 mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="custom_header_name[]" placeholder="@lang('Headers Name')" required>
                                    <input type="text" class="form-control" name="custom_header_value[]" placeholder="@lang('Headers Value')" required>
                                    <span class="input-group-text cursor-pointer cursor-pointer btn-label-danger removeHeader"><i class="tf-icons las la-times-circle"></i></span>
                                </div>
                            </div>`;

                $('.headerFields').append(html);
            });

            $(document).on('click','.removeHeader',function(){
                $(this).closest('.col-sm-12').remove();
            })

            $('.addBody').click(function() {
                let html = `<div class="col-sm-12 mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="custom_body_name[]" placeholder="@lang('Body Name')" required>
                                    <input type="text" class="form-control" name="custom_body_value[]" placeholder="@lang('Body Value')" required>
                                    <span class="input-group-text cursor-pointer cursor-pointer btn-label-danger removeHeader"><i class="tf-icons las la-times-circle"></i></span>
                                </div>
                            </div>`;

                $('.bodyFields').append(html);
            });

            $(document).on('click','.removeBody',function(){
                $(this).closest('.col-sm-12').remove();
            })

        })(jQuery);
    </script>
@endpush

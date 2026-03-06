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
                                <label class="col-sm-3 col-form-label required">@lang('Email Delivery Methods')</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="email_method" required>
                                        <option value="php" @selected(@$setting->mail_config->name == 'php')>@lang('PHP Mail')</option>
                                        <option value="smtp" @selected(@$setting->mail_config->name == 'smtp')>@lang('SMTP')</option>
                                        <option value="sendgrid" @selected(@$setting->mail_config->name == 'sendgrid')>@lang('SendGrid API')</option>
                                        <option value="mailjet" @selected(@$setting->mail_config->name == 'mailjet')>@lang('Mailjet API')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="smtp">
                                <h5 class="card-header border-bottom mb-4">@lang('SMTP Config')</h5>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Host')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="host" value="{{ @$setting->mail_config->host }}" placeholder="e.g. @lang('smtp.demoemail.com')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Port')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="port" value="{{ @$setting->mail_config->port }}" placeholder="@lang('Available port')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Encryption')</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="enc">
                                                <option value="ssl" @selected(@$setting->mail_config->enc == 'ssl')>@lang('SSL')</option>
                                                <option value="tls" @selected(@$setting->mail_config->enc == 'tls')>@lang('TLS')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Username')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="username" value="{{ @$setting->mail_config->username }}" placeholder="@lang('Username')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Password')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="password" value="{{ @$setting->mail_config->password }}" placeholder="@lang('Password')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="sendgrid">
                                <h5 class="card-header border-bottom mb-4">@lang('SendGrid API Config')</h5>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('App Key')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="appkey" value="{{ @$setting->mail_config->appkey }}" placeholder="@lang('SendGrid app key')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border mt-4 configForm" id="mailjet">
                                <h5 class="card-header border-bottom mb-4">@lang('Mailjet API Config')</h5>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Api Public Key')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="public_key" value="{{ @$setting->mail_config->public_key }}" placeholder="@lang('Mailjet api public key')">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">@lang('Api Secret Key')</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="secret_key" value="{{ @$setting->mail_config->secret_key }}" placeholder="@lang('Mailjet api secret key')">
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

    {{-- Test Email Modal --}}
    <div class="modal fade" id="testMailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Test Mail Send')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.notification.email.test') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Sent to')</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" placeholder="@lang('Email address')" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Send')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <button type="button" class="btn btn-label-primary" data-bs-target="#testMailModal" data-bs-toggle="modal">
        <span class="tf-icons las la-envelope me-1"></span> @lang('Test Mail')
    </button>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            var method = '{{ $setting->mail_config->name }}';

            emailMethod(method);
            $('select[name=email_method]').on('change', function() {
                var method = $(this).val();
                emailMethod(method);
            });

            function emailMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }
        })(jQuery);
    </script>
@endpush

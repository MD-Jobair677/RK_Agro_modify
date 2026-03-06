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
                            @forelse($template->shortcodes as $shortcode => $key)
                                <tr>
                                    <td>@php echo "{{". $shortcode ."}}"  @endphp</td>
                                    <td>{{ __($key) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xxl">
            <div class="card mb-4">
                <h5 class="card-header">@lang('Uersatile Short Codes')</h5>
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
                            @foreach ($setting->universal_shortcodes as $shortCode => $codeDetails)
                                <tr>
                                    <td>@php echo "{{". $shortCode ."}}"  @endphp</td>
                                    <td>{{ __($codeDetails) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.notification.template.update', $template->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-6 mt-4">
                <div class="card border">
                    <h5 class="card-header  border-bottom">@lang('Email Template')</h5>
                    <div class="card-body">
                        <div class="row mb-3 mt-4">
                            <label class="col-sm-3 col-form-label required">@lang('Subject')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subject" value="{{ $template->subj }}" placeholder="@lang('Email subject')" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">@lang('Status')</label>
                            <div class="col-sm-9">
                                <label class="switch me-0">
                                    <input type="checkbox" class="switch-input" name="email_status" @if ($template->email_status) checked @endif>
                                    @include('admin.partials.switcher')
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 mb-4">
                            <textarea class="form-control nicEdit" rows="8" name="email_body">{{ $template->email_body }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 mt-4">
                <div class="card border">
                    <h5 class="card-header  border-bottom">@lang('SMS Template')</h5>
                    <div class="card-body">
                        <div class="row mb-3 mt-4">
                            <label class="col-sm-3 col-form-label">@lang('Status')</label>
                            <div class="col-sm-9">
                                <label class="switch me-0">
                                    <input type="checkbox" class="switch-input" name="sms_status" @if ($template->sms_status) checked @endif>
                                    @include('admin.partials.switcher')
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 mb-4">
                            <textarea class="form-control" name="sms_body" rows="10" required>{{ $template->sms_body }}</textarea>
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
@endsection

@push('breadcrumb')
    <a href="{{ $backRoute }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush

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

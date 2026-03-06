@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="{{ $actionRoute }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-xxl-4 col-md-6">
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label required">@lang('Name')</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" value="{{ $method ? @$method->name : old('name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label required">@lang('Currency')</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="currency" value="{{ $method ? @$methodRelation->currency : old('currency') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label required">@lang('Rate')</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text">1 {{ __($setting->site_cur) }} =</span>
                                        <input type="number" step="any" min="0" class="form-control" name="rate" value="{{ $method ? getAmount(@$methodRelation->rate) : old('rate') }}" required>
                                        <span class="input-group-text currencySymbol">{{ @$methodRelation->currency }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <h5 class="card-header  border-bottom">@lang('Limit')</h5>
                                <div class="card-body p-3 pt-0">
                                    <div class="row mb-3 mt-4">
                                        <label class="col-md-4 col-form-label required">@lang('Minimum Amount')</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" class="form-control" name="min_amount" value="{{ $method ? getAmount(@$methodRelation->min_amount) : old('min_amount') }}" required>
                                                <span class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-form-label required">@lang('Maximum Amount')</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" class="form-control" name="max_amount" value="{{ $method ? getAmount(@$methodRelation->max_amount) : old('max_amount') }}" required>
                                                <span class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border h-100">
                                <h5 class="card-header  border-bottom">@lang('Charges')</h5>
                                <div class="card-body p-3 pt-0">
                                    <div class="row mb-3 mt-4">
                                        <label class="col-md-4 col-form-label required">@lang('Fixed Charge')</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" class="form-control" name="fixed_charge" value="{{ $method ? getAmount(@$methodRelation->fixed_charge) : old('fixed_charge') }}" required>
                                                <span class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-form-label required">@lang('Percent Charge')</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" class="form-control" name="percent_charge" value="{{ $method ? getAmount(@$methodRelation->percent_charge) : old('percent_charge') }}" required>
                                                <span class="input-group-text cursor-pointer">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border">
                                <h5 class="card-header  border-bottom">@lang('Guidelines')</h5>
                                <div class="card-body">
                                    <div class="mt-4 mb-4">
                                        <textarea class="form-control nicEdit" rows="8" name="guideline">{{ @$method->guideline }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.formData', [$formHeading])
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

    <x-formGenerator />
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

            $('[name=currency]').on('input', function() {
                $('.currencySymbol').text($(this).val());
            });

            @if (old('currency'))
                $('[name=currency]').trigger('input');
            @endif

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

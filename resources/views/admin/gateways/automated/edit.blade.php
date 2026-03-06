@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.gateway.automated.update', $gateway->code) }}" method="POST">
                    @csrf

                    <input type="hidden" name="alias" value="{{ $gateway->alias }}">
                    <input type="hidden" name="description" value="{{ $gateway->guideline }}">

                    @if ($gateway->guideline)
                        <div class="row">
                            <div class="divider">
                                <div class="divider-text">
                                    <h5 class="text-primary">@lang('Guidelines')</h5>
                                </div>
                            </div>
                            <div class="col-12">
                                <p>{{ __($gateway->guideline) }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($gateway->code < 1000 && $gateway->extra)
                        <div class="row">
                            <div class="divider">
                                <div class="divider-text">
                                    <h5 class="text-primary">@lang('Configurations')</h5>
                                </div>
                            </div>

                            @foreach ($gateway->extra as $key => $param)
                                <div class="col-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label">{{ __(@$param->title) }}</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    value="{{ route($param->value) }}" readonly>
                                                <span
                                                    class="input-group-text cursor-pointer btn btn-label-primary copyInput"
                                                    title="@lang('Copy')">
                                                    <i class="las la-copy"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="divider">
                            <div class="divider-text">
                                <h5 class="text-primary">@lang('Global Setting for') {{ __($gateway->name) }}</h5>
                            </div>
                        </div>

                        @foreach ($parameters->where('global', true) as $key => $param)
                            <div class="col-6">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label required">{{ __(@$param->title) }}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="global[{{ $key }}]"
                                            value="{{ @$param->value }}" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @isset($gateway->currencies)
                        @foreach ($gateway->currencies as $gatewayCurrency)
                            <div class="col-12 mt-4 currencyBody">
                                <input type="hidden" class="currencyText" name="currency[{{ $currencyIndex }}][currency]"
                                    value="{{ $gatewayCurrency->currency }}">
                                <input type="hidden" name="currency[{{ $currencyIndex }}][name]"
                                    value="{{ $gatewayCurrency->name }}">

                                <div class="card mb-4 border">
                                    <div
                                        class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom">
                                        <h5 class="card-header">{{ __($gateway->name) }} -
                                            {{ __($gatewayCurrency->currency) }}</h5>
                                        <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center me-4">
                                            <button type="button" class="btn btn-label-warning decisionBtn"
                                                data-question="@lang('Are you confirming the removal of this gateway currency?')"
                                                data-action="{{ route('admin.gateway.automated.remove', $gatewayCurrency->id) }}">
                                                <span class="tf-icons las la-trash me-1"></span> @lang('Delete')
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="card border">
                                                    <h5 class="card-header  border-bottom">@lang('Limit')</h5>
                                                    <div class="card-body p-3 pt-0">
                                                        <div class="row mb-3 mt-4">
                                                            <label
                                                                class="col-sm-4 col-form-label required">@lang('Minimum')</label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <input type="number" step="any" min="0"
                                                                        class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][min_amount]"
                                                                        value="{{ getAmount($gatewayCurrency->min_amount) }}"
                                                                        required>
                                                                    <span
                                                                        class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label
                                                                class="col-sm-4 col-form-label required">@lang('Maximum')</label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <input type="number" step="any" min="0"
                                                                        class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][max_amount]"
                                                                        value="{{ getAmount($gatewayCurrency->max_amount) }}"
                                                                        required>
                                                                    <span
                                                                        class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="card border">
                                                    <h5 class="card-header  border-bottom">@lang('Charges')</h5>
                                                    <div class="card-body p-3 pt-0">
                                                        <div class="row mb-3 mt-4">
                                                            <label
                                                                class="col-sm-4 col-form-label required">@lang('Fixed')</label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <input type="number" step="any" min="0"
                                                                        class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][fixed_charge]"
                                                                        value="{{ getAmount($gatewayCurrency->fixed_charge) }}"
                                                                        required>
                                                                    <span
                                                                        class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label
                                                                class="col-sm-4 col-form-label required">@lang('Percent')</label>
                                                            <div class="col-sm-8">
                                                                <div class="input-group">
                                                                    <input type="number" step="0.01" min="0"
                                                                        class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][percent_charge]"
                                                                        value="{{ getAmount($gatewayCurrency->percent_charge) }}"
                                                                        required>
                                                                    <span class="input-group-text cursor-pointer">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-4 col-md-6">
                                                <div class="card border">
                                                    <h5 class="card-header  border-bottom">@lang('Currency')</h5>
                                                    <div class="card-body p-3 pt-0">
                                                        <div class="row mb-3 mt-4">
                                                            <label
                                                                class="col-sm-3 col-form-label required">@lang('Symbol')</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control symbl"
                                                                    name="currency[{{ $currencyIndex }}][symbol]"
                                                                    value="{{ $gatewayCurrency->symbol }}"
                                                                    data-crypto="{{ $gateway->crypto }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label
                                                                class="col-sm-3 col-form-label required">@lang('Rate')</label>
                                                            <div class="col-sm-9">
                                                                <div class="input-group">
                                                                    <span class="input-group-text">1
                                                                        {{ __($setting->site_cur) }} =</span>
                                                                    <input type="number" step="any" min="0"
                                                                        class="form-control"
                                                                        name="currency[{{ $currencyIndex }}][rate]"
                                                                        value="{{ getAmount($gatewayCurrency->rate) }}"
                                                                        required>
                                                                    <span
                                                                        class="input-group-text currency_symbol">{{ __($gatewayCurrency->baseSymbol()) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($parameters->where('global', false)->count() != 0)
                                                @php
                                                    $globalParameters = json_decode(
                                                        $gatewayCurrency->gateway_parameter,
                                                    );
                                                @endphp

                                                <div class="col-12 mt-4">
                                                    <div class="card border">
                                                        <h5 class="card-header border-bottom">@lang('Configuration')</h5>
                                                        <div class="card-body">
                                                            <div class="row mt-4">
                                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                                    <div class="col-6">
                                                                        <div class="row mb-3">
                                                                            <label
                                                                                class="col-sm-3 col-form-label required">{{ __($param->title) }}</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="text" class="form-control"
                                                                                    name="currency[{{ $currencyIndex }}][param][{{ $key }}]"
                                                                                    value="{{ $globalParameters->$key }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php $currencyIndex++ @endphp
                        @endforeach
                    @endisset


                    <div class="col-12 mt-4 newMethodCurrency currencyBody d-none">
                        <input type="hidden" class="currencyText" name="currency[{{ $currencyIndex }}][currency]"
                            disabled>
                        <input type="hidden" id="payment_currency_name" name="currency[{{ $currencyIndex }}][name]"
                            disabled>

                        <div class="card mb-4 border">
                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom">
                                <h5 class="card-header payment_currency_name">@lang('Gateway Name')</h5>
                                <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center me-4">
                                    <button type="button" class="btn btn-label-warning newCurrencyRemove">
                                        <span class="tf-icons las la-trash me-1"></span> @lang('Delete')
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-xxl-6 col-md-6">
                                        <div class="card border">
                                            <h5 class="card-header  border-bottom">@lang('Limit')</h5>
                                            <div class="card-body p-3 pt-0">
                                                <div class="row mb-3 mt-4">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Minimum')</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="number" step="any" min="0"
                                                                class="form-control"
                                                                name="currency[{{ $currencyIndex }}][min_amount]" disabled
                                                                required>
                                                            <span
                                                                class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Maximum')</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="number" step="any" min="0"
                                                                class="form-control"
                                                                name="currency[{{ $currencyIndex }}][max_amount]" disabled
                                                                required>
                                                            <span
                                                                class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div class="card border">
                                            <h5 class="card-header  border-bottom">@lang('Charges')</h5>
                                            <div class="card-body p-3 pt-0">
                                                <div class="row mb-3 mt-4">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Fixed')</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="number" step="any" min="0"
                                                                class="form-control"
                                                                name="currency[{{ $currencyIndex }}][fixed_charge]"
                                                                disabled required>
                                                            <span
                                                                class="input-group-text cursor-pointer">{{ __($setting->site_cur) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Percent')</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input type="number" step="0.01" min="0"
                                                                class="form-control"
                                                                name="currency[{{ $currencyIndex }}][percent_charge]"
                                                                disabled required>
                                                            <span class="input-group-text cursor-pointer">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-12 col-md-12">
                                        <div class="card border">
                                            <h5 class="card-header  border-bottom">@lang('Currency')</h5>
                                            <div class="card-body p-3 pt-0">
                                                <div class="row mb-3 mt-4">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Symbol')</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control symbl"
                                                            name="currency[{{ $currencyIndex }}][symbol]"
                                                            data-crypto="{{ $gateway->crypto }}" disabled required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label
                                                        class="col-sm-4 col-form-label required">@lang('Rate')</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-text">1 {{ __($setting->site_cur) }}
                                                                =</span>
                                                            <input type="number" step="any" min="0"
                                                                class="form-control"
                                                                name="currency[{{ $currencyIndex }}][rate]" disabled
                                                                required>
                                                            <span class="input-group-text currency_symbol"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($parameters->where('global', false)->count() != 0)
                                        <div class="col-12 mt-4">
                                            <div class="card border">
                                                <h5 class="card-header border-bottom">@lang('Configuration')</h5>
                                                <div class="card-body p-3 pt-0">
                                                    <div class="row mt-4">
                                                        @foreach ($parameters->where('global', false) as $key => $param)
                                                            <div class="col-6">
                                                                <div class="row mb-3">
                                                                    <label
                                                                        class="col-md-4 col-form-label required">{{ __(@$param->title) }}</label>
                                                                    <div class="col-md-8">
                                                                        <input type="text" class="form-control"
                                                                            name="currency[{{ $currencyIndex }}][param][{{ $key }}]"
                                                                            disabled required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Submit')</button>
                        <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@if (count($supportedCurrencies) > 0)
    @push('breadcrumb')
        <div class="input-group">
            <select class="form-select form-control newCurrencyVal" required>
                <option value="">@lang('Select Currency')</option>

                @forelse($supportedCurrencies as $currency => $symbol)
                    <option value="{{ $currency }}" data-symbol="{{ $symbol }}">{{ __($currency) }} </option>
                @empty
                    <option value="">@lang('No available currency support')</option>
                @endforelse
            </select>

            <button class="btn btn-label-primary input-group-text newCurrencyBtn" data-crypto="{{ $gateway->crypto }}"
                data-name="{{ $gateway->name }}">
                <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
            </button>
        </div>
    @endpush
@endif

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.newCurrencyBtn').on('click', function() {
                var form = $('.newMethodCurrency');
                var getCurrencySelected = $('.newCurrencyVal').find(':selected').val();

                var currency = $(this).data('crypto') == 1 ? 'USD' : `${getCurrencySelected}`;

                if (!getCurrencySelected) return;
                form.find('input').removeAttr('disabled');
                var symbol = $('.newCurrencyVal').find(':selected').data('symbol');
                form.find('.currencyText').val(getCurrencySelected);
                $('.payment_currency_name').text(`${$(this).data('name')} - ${getCurrencySelected}`);
                $('#payment_currency_name').val(`${$(this).data('name')} - ${getCurrencySelected}`);
                form.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $('html, body').height()
                }, 'slow');

                $('.newCurrencyRemove').on('click', function() {
                    form.find('input').val('');
                    form.remove();
                });
            });

            $('.symbl').on('input', function() {
                var curText = $(this).val();
                $(this).parents('.currencyBody').find('.currency_symbol').text(curText);
            });

            $('.copyInput').on('click', function(e) {
                var copybtn = $(this);
                var input = copybtn.closest('.input-group').find('input');
                if (input && input.select) {
                    input.select();
                    try {
                        document.execCommand('SelectAll')
                        document.execCommand('Copy', false, null);
                        input.blur();
                        showToasts('success', `Copied: ${copybtn.closest('.input-group').find('input').val()}`);
                    } catch (err) {
                        alert('Please press Ctrl/Cmd + C to copy');
                    }
                }
            });
        })(jQuery);
    </script>
@endpush

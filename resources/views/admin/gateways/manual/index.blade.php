@extends('admin.layouts.master')

@section('master')
    <div class="row g-3">
        @foreach($methods as $method)
            <div class="col-md-4 col-xxl-3 col-sm-6">
                <div class="card h-100 text-center">
                    <div class="card-body px-3">
                        <h4 class="card-title">{{ $loop->iteration }}. {{ __($method->name) }}</h4>
                        <hr>
                        <p class="card-text mb-0">@lang('Currency') : {{ __($method->singleCurrency->currency) }}</p>
                        <p class="card-text mb-1">@lang('Limit') : {{ $method->singleCurrency->min_amount + 0 }} -
                            {{ $method->singleCurrency->max_amount + 0 }} {{ __($setting->site_cur) }}</p>
                        <p class="card-text mb-1">@lang('Charge') : {{ showAmount($method->singleCurrency->fixed_charge) }}
                            {{ __($setting->site_cur) }}
                            {{ 0 < $method->singleCurrency->percent_charge ? ' + ' . showAmount($method->singleCurrency->percent_charge) . ' %' : '' }}
                        </p>
                        <p class="card-text">@php echo $method->statusBadge @endphp</p>


                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <a href="{{ route('admin.gateway.manual.edit', $method->id) }}" class="btn btn-label-primary">
                                <span class="tf-icons las la-pen me-1"></span> @lang('Edit')
                            </a>

                            @if ($method->status)
                                <button class="btn btn-label-warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this method?')"
                                    data-action="{{ route('admin.gateway.manual.status', $method->id) }}">
                                    <span class="tf-icons las la-ban me-1"></span>
                                    @lang('Inactive')
                                </button>
                            @else
                                <button class="btn btn-label-success decisionBtn" data-question="@lang('Are you confirming the activation of this method?')"
                                    data-action="{{ route('admin.gateway.manual.status', $method->id) }}">
                                    <span class="tf-icons las la-check-circle me-1"></span>
                                    @lang('Active')
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.gateway.manual.new') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
    </a>
@endpush

@extends('admin.layouts.master')

@section('master')
    <div class="row g-3">
        @foreach ($methods as $method)
            <div class="col-md-4 col-xxl-3 col-sm-6">
                <div class="card h-100 text-center">
                    <div class="card-body px-3">
                        <h4 class="card-title">{{ __($method->name) }}</h4>
                        <p class="card-text mb-0">@lang('Currency') : {{ __($method->currency) }}</p>
                        <p class="card-text mb-1">@lang('Limit') : {{ $method->min_amount + 0 }} - {{ $method->max_amount + 0 }} {{ __($setting->site_cur) }}</p>
                        <p class="card-text mb-1">@lang('Charge') : {{ showAmount($method->fixed_charge) }} {{ __($setting->site_cur) }} {{ 0 < $method->percent_charge ? ' + ' . showAmount($method->percent_charge) . ' %' : '' }}</p>
                        <p class="card-text">@php echo $method->statusBadge @endphp</p>

                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <a href="{{ route('admin.withdraw.method.edit', $method->id) }}" class="btn btn-label-primary">
                                <span class="tf-icons las la-pen me-1"></span>
                            </a>

                            @if ($method->status)
                                <button class="btn btn-label-warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this method?')" data-action="{{ route('admin.withdraw.method.status', $method->id) }}">
                                    <span class="tf-icons las la-ban me-1"></span>
                                </button>
                            @else
                                <button class="btn btn-label-success decisionBtn" data-question="@lang('Are you confirming the activation of this method?')" data-action="{{ route('admin.withdraw.method.status', $method->id) }}">
                                    <span class="tf-icons las la-check-circle me-1"></span>
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
    <a href="{{ route('admin.withdraw.method.create') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
    </a>
@endpush

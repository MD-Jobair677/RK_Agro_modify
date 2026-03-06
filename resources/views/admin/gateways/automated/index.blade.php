@extends('admin.layouts.master')
@section('master')
    <div class="row g-3">
        @foreach($gateways as $gateway)
            <div class="col-md-4 col-xxl-3 col-sm-6">
                <div class="card h-100 text-center">
                    <div class="card-body px-3">
                        <h4 class="card-title">{{ __($gateway->name) }}</h4>
                     
                        <p class="card-text mb-0">@lang('Total Currency') : {{ collect($gateway->supported_currencies)->count() }}</p>
                        <p class="card-text mb-1">@lang('Active Currency') : {{ $gateway->currencies->count() }}</p>
                        <p class="card-text">@php echo $gateway->statusBadge @endphp</p>

                        
                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <a href="{{ route('admin.gateway.automated.edit', $gateway->alias) }}" class="btn btn-icon btn-label-primary">
                                <span class="tf-icons las la-edit me-1"></span>
                            </a>

                            @if ($gateway->status)
                                <button class="btn btn-icon btn-label-warning decisionBtn" data-question="@lang('Are you confirming the inactivation of this gateway?')" data-action="{{ route('admin.gateway.automated.status', $gateway->id) }}">
                                    <span class="tf-icons las la-ban me-1"></span>
                                </button>
                            @else
                                <button class="btn btn-icon btn-label-success decisionBtn" data-question="@lang('Are you confirming the activation of this gateway?')" data-action="{{ route('admin.gateway.automated.status', $gateway->id) }}">
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

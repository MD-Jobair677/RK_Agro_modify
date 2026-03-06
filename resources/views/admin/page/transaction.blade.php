@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="form-repeater" action="">
                        <div data-repeater-list="group-a">
                            <div data-repeater-item>
                                <div class="row gx-3">
                                    <div class="col-xl-8 col-xxl-4 col-sm-6 mb-0">
                                        <label class="form-label" for="form-repeater-1-1">@lang('TRX/Username')</label>
                                        <input class="form-control" name="search" value="{{ request()->search }}">
                                    </div>
                                    <div class="col-xl-4 col-xxl-3 col-sm-6 mb-0">
                                        <label class="form-label">@lang('Remark')</label>
                                        <select class="form-select" name="remark">
                                            <option value="">@lang('Any')</option>
                                            @foreach ($remarks as $remark)
                                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                                    {{ __(keyToTitle($remark->remark)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-xxl-3 col-sm-6 mb-0">
                                        <label class="form-label">@lang('Date')</label>
                                        <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">
                                    </div>
                                    <div class="col-xl-2 d-flex align-items-center mb-0">
                                        <button class="btn btn-label-primary mt-4 w-100" type="submit" data-repeater-delete>
                                            <i class="las la-filter me-1"></i>
                                            <span class="align-middle">@lang('Filter')</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('TRX')</th>
                                <th>@lang('Transacted')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('After Balance')</th>
                                <th>@lang('Details')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $transaction->user ? $transaction->user->fullname : $transaction->sender_name }}</span>
                                            <br>
                                            <span class="small">
                                                @if ($transaction->user)
                                                    <a href="{{ appendQuery('search', $transaction->user->username) }}">
                                                        <span>@</span>{{ $transaction->user->username }}
                                                    </a>
                                                @else
                                                    <span class="text-primary">{{ $transaction->sender_email }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $transaction->trx }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            {{ showDateTime($transaction->created_at) }}
                                            <br>
                                            {{ diffForHumans($transaction->created_at) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="@if ($transaction->trx_type == '+') text-success @else text-danger @endif">
                                            {{ showAmount($transaction->amount) . ' ' . __($setting->site_cur) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-danger">{{ showAmount($transaction->charge) . ' ' . __($setting->site_cur) }}</span>
                                    </td>
                                    <td>
                                        {{ showAmount($transaction->post_balance) . ' ' . __($setting->site_cur) }}
                                    </td>
                                    <td>
                                        {{__($transaction->details) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center"> {{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($transactions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.datepicker-here').on('input keyup keydown keypress', function() {
                return false;
            });

            if (!$('.datepicker-here').val()) $('.datepicker-here').datepicker();
            
        })(jQuery);
    </script>
@endpush

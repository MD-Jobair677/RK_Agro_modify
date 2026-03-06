@extends('admin.layouts.master')

@section('master')
   <!-- users -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <a href="{{ route('admin.user.index') }}" class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1">{{ $widget['totalUsersCount'] }}</h3>
                                        <p class="mb-0">@lang('Total Users')</p>
                                    </div>
                                    <span class="badge bg-label-primary rounded p-2 me-sm-4">
                                        <i class="las la-users fs-3"></i>
                                    </span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </a>
                            <a href="{{ route('admin.user.active') }}" class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1">{{ $widget['activeUsersCount'] }}</h3>
                                        <p class="mb-0">@lang('Active Users')</p>
                                    </div>
                                    <span class="badge bg-label-info rounded p-2 me-lg-4">
                                        <i class="la las la-user-check fs-3"></i>
                                    </span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </a>
                            <a href="{{ route('admin.user.email.unconfirmed') }}" class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                    <div>
                                        <h3 class="mb-1">{{ $widget['unconfirmedEmailUsersCount'] }}</h3>
                                        <p class="mb-0">@lang('Unconfirmed Email')</p>
                                    </div>
                                    <span class="badge bg-label-warning rounded p-2 me-sm-4">
                                        <i class="las la-envelope-open-text fs-3"></i>
                                    </span>
                                </div>
                            </a>
                            <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h3 class="mb-1">{{ $widget['unconfirmedMobileUsersCount'] }}</h3>
                                        <p class="mb-0">@lang('Unconfirmed Mobile')</p>
                                    </div>
                                    <span class="badge bg-label-danger rounded p-2">
                                        <i class="las la-phone-volume fs-3"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- deposit -->
    {{-- <div class="row">
        <a href="{{ route('admin.deposit.successful') }}" class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-success">@lang('Donated')</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $setting->site_sym }}{{ showAmount($widget['depositDone']) }}</h4>
                            </div>
                            <small>@lang('Total donated amount')</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-success rounded p-2">
                                <i class="las la-hand-holding-usd fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.deposit.pending') }}" class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-warning">
                            <p class="card-text text-warning">@lang('Pending')</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $widget['depositPending'] }}</h4>
                            </div>
                            <small>@lang('Pending deposit count')</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-warning rounded p-2">
                                <i class='las la-spinner fs-3'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.deposit.rejected') }}" class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-danger">
                            <p class="card-text text-danger">@lang('Cancelled')</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $widget['depositCancelled'] }}</h4>
                            </div>
                            <small>@lang('Cancelled deposit count')</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-danger rounded p-2">
                                <i class='las la-times-circle fs-3'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.deposit.list') }}" class="col-lg-3 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text text-info">@lang('Charge')</p>
                            <div class="d-flex align-items-end mb-2">
                                <h4 class="card-title mb-0 me-2">{{ $setting->cur_sym }}{{ showAmount($widget['depositCharge']) }}</h4>
                            </div>
                            <small>@lang('Total charge for donated amount')</small>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="las la-money-bill fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}
 <!-- withdrawal -->
    {{-- <div class="row">
        <a href="{{ route('admin.withdraw.approved') }}" class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="las la-university fs-3"></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawDone']) }}</h4>
                    </div>
                    <p class="mb-1 text-primary">@lang('Withdrawn')</p>
                    <p class="mb-0">
                        <small>@lang('Total withdrawn amount')</small>
                    </p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.withdraw.pending') }}" class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class='las la-circle-notch fs-3'></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $widget['withdrawPending'] }}</h4>
                    </div>
                    <p class="mb-1 text-warning">@lang('Pending')</p>
                    <p class="mb-0">
                        <small>@lang('Pending withdrawal count')</small>
                    </p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.withdraw.rejected') }}" class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class='las la-ban fs-3'></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $widget['withdrawCancelled'] }}</h4>
                    </div>
                    <p class="mb-1 text-danger">@lang('Cancelled')</p>
                    <p class="mb-0">
                        <small>@lang('Cancelled withdrawal count')</small>
                    </p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.withdraw.index') }}" class="col-sm-6 col-lg-3 mb-4">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="las la-money-bill fs-3"></i>
                            </span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawCharge']) }}</h4>
                    </div>
                    <p class="mb-1 text-info">@lang('Charge')</p>
                    <p class="mb-0">
                        <small>@lang('Total charge for withdrawn amount')</small>
                    </p>
                </div>
            </div>
        </a>
    </div> --}}
@endsection


{{-- @push('page-script')
    <script>
        "use strict";
        
        var options = {
            series: [{
                name: 'Total Deposit',
                data: [
                    @foreach ($months as $month)
                        {{ getAmount(@$depositsMonth->where('months', $month)->first()->depositAmount) }},
                    @endforeach
                ]
            }, {
                name: 'Total Withdraw',
                data: [
                    @foreach ($months as $month)
                        {{ getAmount(@$withdrawalMonth->where('months', $month)->first()->withdrawAmount) }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($months),
            },
            yaxis: {
                title: {
                    text: "{{ __($setting->cur_sym) }}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "{{ __($setting->cur_sym) }}" + val + " "
                    }
                }
            }
        };
    </script>
@endpush --}}

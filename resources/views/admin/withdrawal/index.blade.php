@extends('admin.layouts.master')

@section('master')
    <div class="row">
        @if (request()->routeIs('admin.withdraw.index') || request()->routeIs('admin.withdraw.method') || request()->routeIs('admin.user.withdraw') || request()->routeIs('admin.user.withdraw.method'))
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-widget-separator-wrapper">
                        <div class="card-body card-widget-separator">
                            <div class="row gy-4 gy-sm-1">
                                <a href="{{ route('admin.withdraw.approved') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($done) }} {{ __($setting->site_cur) }}</h3>
                                            <p class="mb-0">@lang('Approved Withdrawals')</p>
                                        </div>
                                        <span class="badge bg-label-success rounded p-2 me-sm-4">
                                            <i class="las la-check-circle fs-3"></i>
                                        </span>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none me-4">
                                </a>
                                <a href="{{ route('admin.withdraw.pending') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($pending) }} {{ __($setting->site_cur) }}</h3>
                                            <p class="mb-0">@lang('Pending Withdrawals')</p>
                                        </div>
                                        <span class="badge bg-label-warning rounded p-2 me-sm-4">
                                            <i class="las la-spinner fs-3"></i>
                                        </span>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none me-4">
                                </a>
                                <a href="{{ route('admin.withdraw.rejected') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($canceled) }} {{ __($setting->site_cur) }}</h3>
                                            <p class="mb-0">@lang('Rejected Withdrawals')</p>
                                        </div>
                                        <span class="badge bg-label-danger rounded p-2">
                                            <i class="lar la-times-circle fs-3"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Gateway | Transaction')</th>
                                <th>@lang('Launched')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Conversion')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($withdrawals as $withdraw)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold">
                                                <a href="{{ appendQuery('method', $withdraw->method->id) }}">
                                                    {{ __($withdraw->method->name) }}
                                                </a>
                                            </span>
                                            <br>
                                            <small title="@lang('Transaction Number')">{{ $withdraw->trx }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ showDateTime($withdraw->created_at) }}
                                            <br>
                                            {{ diffForHumans($withdraw->created_at) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ appendQuery('search', $withdraw->user->username) }}">
                                                    <span>@</span>{{ $withdraw->user->username }}
                                                </a>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ __($setting->cur_sym) . showAmount($withdraw->amount) }} - <span class="text-danger" title="@lang('Charge')">{{ showAmount($withdraw->charge) }}</span>
                                            <br>
                                            <strong title="@lang('Amount Without Charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) . ' ' . __($setting->site_cur) }}
                                            </strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            1 {{ __($setting->site_cur) }} = {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}
                                            <br>
                                            <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($withdraw->status == ManageStatus::PAYMENT_PENDING)
                                            <span class="badge bg-label-warning">@lang('Pending')</span>
                                        @elseif ($withdraw->status == ManageStatus::PAYMENT_SUCCESS)
                                            <span class="badge bg-label-success">@lang('Approved')</span>
                                        @elseif ($withdraw->status == ManageStatus::PAYMENT_CANCEL)
                                            <span class="badge bg-label-danger">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                            data-bs-toggle      = "offcanvas"
                                            data-bs-target      = "#offcanvasBoth"
                                            aria-controls       = "offcanvasBoth"
                                            data-date           = "{{ showDateTime($withdraw->created_at) }}"
                                            data-trx            = "{{ $withdraw->trx }}"
                                            data-username       = "{{ $withdraw->user->username }}"
                                            data-method         = "{{ __($withdraw->method->name) }}"
                                            data-amount         = "{{ showAmount($withdraw->amount) }} {{ __($setting->site_cur) }}"
                                            data-charge         = "{{ showAmount($withdraw->charge) }} {{ __($setting->site_cur) }}"
                                            data-after_charge   = "{{ showAmount($withdraw->after_charge) }} {{ __($setting->site_cur) }}"
                                            data-rate           = "1 {{ __($setting->site_cur) }} = {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}"
                                            data-payable        = "{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}"
                                            data-status         = "{{ $withdraw->status }}"
                                            data-user_data      = "{{ json_encode($withdraw->withdraw_information) }}"
                                            data-admin_feedback = "{{ $withdraw->admin_feedback }}"
                                            data-url="{{ route('admin.file.download', ['filePath' => 'verify']) }}"
                                        >
                                            <span class="tf-icons las la-info-circle me-1"></span> @lang('Details')
                                        </button>

                                        @if($withdraw->status == ManageStatus::PAYMENT_PENDING)
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-label-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">@lang('Action')</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item approveBtn" data-id="{{ $withdraw->id }}" data-amount="{{ showAmount($withdraw->final_amount) }} {{ $withdraw->currency }}">
                                                            <i class="las la-check-circle fs-6 link-success"></i> @lang('Approve')
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <button type="button" class="dropdown-item cancelBtn" data-id="{{ $withdraw->id }}">
                                                            <i class="lar la-times-circle fs-6 link-danger"></i> @lang('Reject')
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($withdrawals->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($withdrawals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth" aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h3 id="offcanvasBothLabel" class="offcanvas-title">@lang('Withdraw Details')</h3>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="basicData"></div>
            <div class="userData"></div>
            <button type="button" class="btn btn-secondary d-grid w-100 mt-4" data-bs-dismiss="offcanvas">
                @lang('Close')
            </button>
        </div>
    </div>

    <div class="modal-onboarding modal fade animate__animated" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.withdraw.approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="modal-body p-0 text-center">
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">@lang('Approve Withdrawal Confirmation')</h4>
                            <div class="onboarding-info question">
                                @lang('Have you sent') <span class="fw-bold withdraw-amount text-primary"></span>?
                            </div>

                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="admin_feedback" placeholder="@lang('For example, furnish the specifics, such as the transaction number.')" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 justify-content-center">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-onboarding modal fade animate__animated" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.withdraw.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="modal-body p-0 text-center">
        
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">@lang('Reject Withdrawal Confirmation')</h4>

                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <h5>@lang('Reason')</h5>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="admin_feedback" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 justify-content-center">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="TRX/Username" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.detailBtn').on('click', function() {
                let userData   = $(this).data('user_data');
                let statusHtml = ``;

                if ($(this).data('status') == 1) {
                    statusHtml += `<span class="badge bg-label-success">@lang('Done')</span>`;
                } else if ($(this).data('status') == 2) {
                    statusHtml += `<span class="badge bg-label-warning">@lang('Pending')</span>`;
                } else {
                    statusHtml += `<span class="badge bg-label-danger">@lang('Canceled')</span>`
                }

                let basicHtml = `<div class="mb-4">
                                    <h5>@lang('Basic Information')</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Date')</b>
                                            <span>${$(this).data('date')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Trx Number')</b>
                                            <span>${$(this).data('trx')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Username')</b>
                                            <span>${$(this).data('username')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Method')</b>
                                            <span>${$(this).data('method')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Amount')</b>
                                            <span>${$(this).data('amount')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Charge')</b>
                                            <span>${$(this).data('charge')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('After Charge')</b>
                                            <span>${$(this).data('after_charge')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Rate')</b>
                                            <span>${$(this).data('rate')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Payable')</b>
                                            <span>${$(this).data('payable')}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>@lang('Status')</b>
                                            ${statusHtml}
                                        </li>`;

                if ($(this).data('admin_feedback')) {
                    basicHtml += `<li class="list-group-item">
                                    <b class="text-primary">@lang('Admin Feedback')</b>
                                    <p class="mt-2 mb-0 d-none d-sm-block">${$(this).data('admin_feedback')}</p>
                                </li>`;
                }

                basicHtml += `</ul>
                    </div>`;

                $('.basicData').html(basicHtml);

                if (userData) {
                    let fileDownloadUrl = $(this).data('url')
                    let infoHtml = `<div class="mt-3">
                                        <h5>@lang('Withdrawal Related Data')</h5>
                                        <ul class="list-group">`;

                    userData.forEach(element => {
                        if (!element.value) return;

                        if (element.type != 'file') {
                            infoHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>${element.name}</b>
                                            <span>${element.value}</span>
                                        </li>`;
                        } else {
                            infoHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                            <b>${element.name}</b>
                                            <span>
                                                <a href="${fileDownloadUrl}&fileName=${element.value}">
                                                    <i class="las la-arrow-circle-down"></i> @lang('Attachment')
                                                </a>
                                            </span>
                                        </li>`;
                        }
                    });

                    infoHtml += `</ul>
                    </div>`;

                    $('.userData').html(infoHtml);
                }
            });

            $('.approveBtn').on('click', function() {
                let modal = $('#approveModal');

                modal.find('[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.modal('show');
            });

            $('.cancelBtn').on('click', function() {
                let modal = $('#cancelModal');

                modal.find('[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

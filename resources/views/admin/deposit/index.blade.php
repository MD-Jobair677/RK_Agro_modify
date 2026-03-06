@extends('admin.layouts.master')
@section('master')
    <div class="row">
        @if(request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method') ||
        request()->routeIs('admin.users.deposits') || request()->routeIs('admin.users.deposits.method'))
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-widget-separator-wrapper">
                        <div class="card-body card-widget-separator">
                            <div class="row gy-4 gy-sm-1">
                                <a href="{{ route('admin.deposit.successful') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($successful)}}{{ __($setting->site_cur) }} </h3>
                                            <p class="mb-0">@lang('Successful Deposit')</p>
                                        </div>
                                        <span class="badge bg-label-success rounded p-2 me-sm-4">
                                            <i class="las la-check-circle fs-3"></i>
                                        </span>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none me-4">
                                </a>
                                <a href="{{ route('admin.deposit.pending') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($pending) }}{{ __($setting->site_cur) }}</h3>
                                            <p class="mb-0">@lang('Pending Deposit')</p>
                                        </div>
                                        <span class="badge bg-label-warning rounded p-2 me-sm-4">
                                            <i class="las la-spinner fs-3"></i>
                                        </span>
                                    </div>
                                    <hr class="d-none d-sm-block d-lg-none me-4">
                                </a>
                                <a href="{{ route('admin.deposit.rejected') }}" class="col-sm-6 col-lg-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h3 class="mb-1">{{ showAmount($rejected) }}{{ __($setting->site_cur) }}</h3>
                                            <p class="mb-0">@lang('Cancelled Deposit Amount')</p>
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
                                <th>@lang('Gateway')</th>
                                <th>@lang('Transaction')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Conversion')</th>
                                <th>@lang('Created at')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($deposits as $deposit)
                            @php
                            $details = $deposit->detail ? json_encode($deposit->detail) : null;
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-bold"> 
                                        <a href="{{ appendQuery('method',@$deposit->gateway->alias) }}">{{__(@$deposit->gateway->name) }}</a> 
                                    </span>
                                </td>

                                <td>
                                    {{ $deposit->trx }}
                                </td>
                                <td>
                                    <span class="fw-bold">
                                        <a href="{{ appendQuery('search', $deposit->user->username) }}">
                                        {{ $deposit->user->username }}
                                        </a>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        {{ $setting->cur_sym . showAmount($deposit->amount) }} + <span class="text-danger" title="@lang('Charge')">{{ showAmount($deposit->charge) }}</span>
                                        <br>
                                        <strong title="@lang('Amount With Charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge) . ' ' . __($setting->site_cur) }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        1 {{ $setting->site_cur }} = {{ showAmount($deposit->rate, 4) . ' ' . __($deposit->method_currency) }}
                                        <br>
                                        <strong>
                                            {{ showAmount($deposit->final_amount) . ' ' . __($deposit->method_currency) }}
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    {{ showDateTime($deposit->created_at) }}
                                </td>
                                <td>
                                    @php echo $deposit->statusBadge @endphp
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-label-info depositViewBtn"
                                            data-bs-toggle      = "offcanvas"
                                            data-bs-target      = "#offcanvasBoth"
                                            aria-controls       = "offcanvasBoth"
                                            data-gatewayname    = "{{ @$deposit->gateway->name }}"
                                            data-date           = "{{ showDateTime($deposit->created_at) }}"
                                            data-trx            = "{{ $deposit->trx }}"
                                            data-username       = "{{ @$deposit->user->username }}"
                                            data-user_data      = "{{ json_encode($deposit->details) }}"
                                            data-admin_feedback = "{{ $deposit->admin_feedback }}"
                                            data-url="{{ route('admin.file.download', ['filePath' => 'verify']) }}"
                                            >
                                            <span class="tf-icons las la-info-circle me-1"></span> @lang('Details')
                                        </button>

                                        @if($deposit->status == ManageStatus::PAYMENT_PENDING)
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                @lang('Action')
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Do you want to approve this deposit?')" data-action="{{ route('admin.deposit.approve', $deposit->id) }}">
                                                        <i class="las la-check-circle fs-6 link-success"></i> @lang('Approve')
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item cancelBtn" data-action="{{ route('admin.deposit.reject', $deposit->id) }}">
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
                                <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($deposits->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($deposits) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth" aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h3 id="offcanvasBothLabel" class="offcanvas-title">@lang('Deposit Details')</h3>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="basicData"></div>
            <div class="userData"></div>
            <button type="button" class="btn btn-secondary d-grid w-100 mt-4" data-bs-dismiss="offcanvas">
                @lang('Close')
            </button>
        </div>
    </div>

    <x-decisionModal />

    <div class="modal-onboarding modal fade animate__animated" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body p-0 text-center">
                        
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">@lang('Make Your Decision')</h4>
                            <div class="onboarding-info question">
                                @lang('Do you want to reject this deposit?')
                            </div>
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
    
            $('.depositViewBtn').on('click', function() {
                let $this = $(this);
                let gatewayname = $this.data('gatewayname');
                let date = $this.data('date');
                let trx = $this.data('trx');
                let username = $this.data('username');
                let feedback = $this.data('admin_feedback');
                let userData = $this.data('user_data');
                let url = $this.data('url');
    
                let html = `<div class="mb-4">
                                <h5>@lang('Deposit Via') <strong>${gatewayname}</strong></h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Date')</b>
                                        ${date}
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Transaction Number')</b>
                                        ${trx}
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Username')</b>
                                        <span>${username}</span>
                                    </li>`;
    
                if (feedback) {
                    html += `<li class="list-group-item">
                                <b class="text-primary">@lang('Admin Feedback')</b>
                                <p class="mt-2 mb-0 d-none d-sm-block">${feedback}</p>
                            </li>`;
                }
    
                html += `</ul>
                        </div>`;
    
                $('.basicData').html(html);
    
                if (userData) {
                    let infoHtml = `<div class="mt-4">
                                        <h5>@lang('Deposit Information')</h5>
                                        <ul class="list-group">`;
    
                    userData.forEach(element => {
                        if (!element.value) return;
    
                        let attachmentHtml = element.type !== 'file' ?
                            `<span>${element.value}</span>` :
                            `<span><a href="${fileDownloadUrl}&fileName=${element.value}"><i class="las la-arrow-circle-down"></i> @lang('Attachment')</a></span>`;
    
                        infoHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>${element.name}</b>
                                        ${attachmentHtml}
                                    </li>`;
                    });
    
                    infoHtml += `</ul>
                                </div>`;
    
                    $('.userData').html(infoHtml);
                } else {
                    $('.userData').empty();
                }
            });
    
            $('.cancelBtn').on('click', function () {
                let cancelModal = $('#cancelModal');
                cancelModal.find('form').attr('action', $(this).data('action'));
                cancelModal.modal('show');
            });
        })(jQuery);
    </script>
    
@endpush

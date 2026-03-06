@extends('admin.layouts.master')

@section('master')

    <!-- card -->
    <div class="row">
        <div class="col-xxl-12">
            <div class="card mb-4">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <a href="{{ route('admin.transaction.index') }}?search={{ $user->username }}"
                                class="col-sm-6 col-lg-4">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1">{{ showAmount($user->balance) . ' ' . __($setting->site_cur) }}
                                        </h3>
                                        <p class="mb-0">@lang('Balance')</p>
                                    </div>
                                    <span class="badge bg-label-info rounded p-2 me-sm-4">
                                        <i class="las la-coins fs-3"></i>
                                    </span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </a>
                            <a href="{{ route('admin.deposit.index') }}?search={{ $user->id }}"
                                class="col-sm-6 col-lg-4">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1">{{ showAmount($totalDeposit) }}{{ __($setting->site_cur) }}</h3>
                                        <p class="mb-0">@lang('Total Deposits')</p>
                                    </div>
                                    <span class="badge bg-label-success rounded p-2 me-sm-4">
                                        <i class="las la-wallet fs-3"></i>
                                    </span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </a>
                            <a href="{{ route('admin.withdraw.index') }}?search={{ $user->username }}"
                                class="col-sm-6 col-lg-4">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-1">{{ showAmount($totalWithdrawal) . ' ' . __($setting->site_cur) }}
                                        </h3>
                                        <p class="mb-0">@lang('Total Withdrawals')</p>
                                    </div>
                                    <span class="badge bg-label-warning rounded p-2 me-sm-4">
                                        <i class="las la-money-bill-wave fs-3"></i>
                                    </span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- add,deduct,login btn -->
     <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-4 col-sm-6">
                            <button type="button" class="btn btn-label-success w-100 balanceUpdateBtn" data-act="add">
                                <span class="las la-plus-circle me-1"></span>@lang('Add Balance')
                            </button>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <button type="button" class="btn btn-label-danger w-100 balanceUpdateBtn" data-act="sub">
                                <span class="las la-minus-circle me-1"></span>@lang('Deduct Balance')
                            </button>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            @if ($user->status)
                                <button type="button" class="btn btn-label-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#statusModal">
                                    <span class="las la-user-slash me-1"></span>@lang('Ban User')
                                </button>
                            @else
                                <button type="button" class="btn btn-label-success w-100" data-bs-toggle="modal"
                                    data-bs-target="#statusModal">
                                    <span class="las la-undo-alt me-1"></span>@lang('Unban User')
                                </button>
                            @endif
                        </div>
                        @if($user->kyc_data)
                        <div class="col-lg-4 col-sm-6">
                            <a href="{{ route('admin.user.kyc.details', $user->id) }}" class="btn btn-label-warning w-100">
                                <span class="las la-key me-1"></span>@lang('KYC')
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- basic info form -->
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <h5 class="card-header">@lang('Profile Information for') {{ $user->fullname }}</h5>
                <hr class="mt-0">
                <form class="card-body" action="{{ route('admin.user.update', $user->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label required">@lang('First Name')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="text" class="form-control" name="firstname"
                                        value="{{ $user->firstname }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label required">@lang('Last Name')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="text" class="form-control" name="lastname"
                                        value="{{ $user->lastname }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label required">@lang('Email')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                        required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label required">@lang('Mobile')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-text mobile-code"></span>
                                        <input type="text" class="form-control" name="mobile" id="mobile"
                                            value="{{ $user->mobile }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label required">@lang('Country')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <select class="form-select" name="country" required>
                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label">@lang('City')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="text" class="form-control" name="city"
                                        value="{{ @$user->address->city }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label">@lang('State')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="text" class="form-control" name="state"
                                        value="{{ @$user->address->state }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-sm-4 col-form-label">@lang('Zip Code')</label>
                                <div class="col-lg-9 col-sm-8">
                                    <input type="text" class="form-control" name="zip"
                                        value="{{ @$user->address->zip }}">
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4">
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row mb-3 d-flex align-items-center border-end">
                                <label class="col-8 col-form-label required">@lang('Email Confirmation')</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <label class="switch me-0">
                                        <input type="checkbox" class="switch-input" name="ec"
                                            @if ($user->ec) checked @endif>
                                        @include('admin.partials.switcher')
                                    </label>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row mb-3 d-flex align-items-center border-end">
                                <label class="col-8 col-form-label required">@lang('Mobile Confirmation')</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <label class="switch me-0">
                                        <input type="checkbox" class="switch-input" name="sc"
                                            @if ($user->sc) checked @endif>
                                        @include('admin.partials.switcher')
                                    </label>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row mb-3 d-flex align-items-center border-end">
                                <label class="col-8 col-form-label required">@lang('2FA Authorization')</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <label class="switch me-0">
                                        <input type="checkbox" class="switch-input" name="ts"
                                            @if ($user->ts) checked @endif>
                                        @include('admin.partials.switcher')
                                    </label>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row mb-3 d-flex align-items-center">
                                <label class="col-8 col-form-label required">@lang('KYC Verification')</label>
                                <div class="col-4 d-flex justify-content-end">
                                    <label class="switch me-0">
                                        <input type="checkbox" class="switch-input" name="kc"
                                            @if ($user->kc == ManageStatus::VERIFIED) checked @endif>
                                        @include('admin.partials.switcher')
                                    </label>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-2">@lang('Submit')</button>
                            <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ad sub balance modal -->
    <div class="modal fade" id="balanceUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('admin.user.add.sub.balance', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="act">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">@lang('Amount')</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" step="any" class="form-control" name="amount" min="0"
                                    placeholder="@lang('Kindly enter an amount that is positive')" required>
                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">@lang('Remark')</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="remark" placeholder="@lang('Remark')" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <hr class="mt-0">
                <div class="modal-footer justify-content-end">
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    <button type="button" class="btn btn-label-secondary"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ban user modal -->
    <div class="modal-onboarding modal fade animate__animated" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.user.status', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-body p-0">
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">
                                {{ $user->status ? trans('Ban User') : trans('Unban User') }}
                            </h4>

                            <div class="onboarding-info question">
                                @if ($user->status)
                                    @lang('Banning this user will restrict their access to the dashboard')
                                @else
                                    @lang('Are you confirming the action to unban this user?')
                                    <div class="mt-3">
                                        <h5>@lang('Banning reason was')</h5>
                                        <p>{{ __($user->ban_reason) }}</p>
                                    </div>
                                @endif
                            </div>

                            @if ($user->status)
                                <div class="row">
                                    <div class="col-sm-12 mt-3">
                                        <label class="col-sm-3 col-form-label required">@lang('Reason')</label>
                                        <textarea class="form-control" name="ban_reason" rows="3" required></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer border-0 justify-content-end">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.balanceUpdateBtn').on('click', function() {
                let modal = $('#balanceUpdateModal');
                let act = $(this).data('act');

                modal.find('[name=act]').val(act);

                if (act == 'add') {
                    modal.find('.modal-title').text(`@lang('Add Balance')`);
                } else {
                    modal.find('.modal-title').text(`@lang('Subtract Balance')`);
                }

                modal.modal('show');
            });

            let mobileElement = $('.mobile-code');

            $('[name=country]').change(function() {
                mobileElement.text(`+${$('[name=country] :selected').data('mobile_code')}`);
            });

            $('[name=country]').val('{{ @$user->country_code }}');

            let dialCode = $('[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $user->mobile }}`;
            mobileNumber = mobileNumber.replace(dialCode, '');

            $('[name=mobile]').val(mobileNumber);

            mobileElement.text(`+${dialCode}`);
        })(jQuery);
    </script>
@endpush

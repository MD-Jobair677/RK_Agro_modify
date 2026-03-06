@extends('admin.layouts.master')
@section('master')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($user->kyc_data)
                        <ul class="list-group">
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ __($val->name) }}
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            @if ($val->value)
                                                <a href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}" class="btn btn-primary"> <i class="las la-file"></i> @lang('Attachment') </a>
                                            @else
                                                @lang('No File')
                                            @endif
                                        @else
                                            <p>{{ __($val->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">@lang('KYC data not found')</p>
                    @endif

                    @if ($user->kc == 2)
                        <div class="d-flex flex-wrap justify-content-end mt-3">
                            <button class="btn btn-danger me-3 decisionBtn" data-question="@lang('Are you sure to reject this documents?')"
                                data-action="{{ route('admin.user.kyc.cancel', $user->id) }}"><i
                                    class="las la-ban"></i>@lang('Reject')</button>
                            <button class="btn btn-success decisionBtn" data-question="@lang('Are you sure to approve this documents?')"
                                data-action="{{ route('admin.user.kyc.approve', $user->id) }}"><i
                                    class="las la-check"></i>@lang('Approve')</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-decisionModal />
@endsection

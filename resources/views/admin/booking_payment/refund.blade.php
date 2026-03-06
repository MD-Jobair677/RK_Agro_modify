@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.booking.store.refund.payment') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Refund Amount')</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="refund_amount" value="{{$booking->total_payment_amount - $booking->sale_price }}"
                                        placeholder="@lang('Enter your payment refund amount')" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 sds">@lang('Refund')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.booking.index') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush

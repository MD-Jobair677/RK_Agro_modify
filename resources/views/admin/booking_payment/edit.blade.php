@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.booking.update.payment') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{ $booking->id }}" name="booking_id">
                    <input type="hidden" value="{{ $BookingPayment->id }}" name="payment_id">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 mb-3">
                            <div class="form-group">
                                <label class="form-label required">@lang('Payment Date')</label>
                                <input name="payment_date" id="datepicker" type="text" data-range="false"
                                    data-language="en" class="datepicker-here form-control" data-position='bottom right'
                                    placeholder="@lang('Payment Date')" autocomplete="off"
                                    value="{{ $BookingPayment->payment_date }}">
                            </div>
                        </div>
                      

                        <div class="col-lg-3 col-md-3">
                            <div class="form-group">
                                <label class="form-label required">@lang('Cattle Name/number')</label>

                                {{-- <input type="text" class="form-control" name="cattle_name"
                                        placeholder="@lang('Enter your cattle name/number')" required> --}}

                                <div class="mb-3">


                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach ($booking->cattle_bookings as $cb)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="cattle_booking_ids[]"
                                                    value="{{ $cb->cattle_id }}" id="cattle{{ $cb->id }}">
                                                    {{-- @php
                                                        dd($cb->cattle_id);
                                                    @endphp --}}

                                                <label class="form-check-label" for="cattle{{ $cb->id . ' ' . $cb->name }}">
                                                    {{ optional($cb->cattle)->tag_number }} </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class="col-lg-3 col-md-3 mb-3">
                            <div class="form-group mt-2">
                                <label class="form-label required">@lang('Payment Method')</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="">-- @lang('Select Payment Method') --</option>
                                    <option value="Cash" {{ $BookingPayment->payment_method == 'Cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="Bank" {{ $BookingPayment->payment_method == 'Bank' ? 'selected' : '' }}>Bank
                                    </option>
                                    <option value="Bkash" {{ $BookingPayment->payment_method == 'Bkash' ? 'selected' : '' }}>Bkash
                                    </option>
                                    <option value="Rocket" {{ $BookingPayment->payment_method == 'Rocket' ? 'selected' : '' }}>Rocket
                                    </option>
                                    <option value="Nagad" {{ $BookingPayment->payment_method == 'Nagad' ? 'selected' : '' }}>Nagad
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3">
                            <div class="form-group">
                                <label class="form-label required">@lang('Amount')</label>

                                <input type="number" class="form-control" name="amount"
                                    value="{{ $BookingPayment->payment }}" placeholder="@lang('Enter your payment amount')" required>

                            </div>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 sds">@lang('Update')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.booking.payment.list', $booking->id) }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush
@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush
@push('page-script')
    <script>
        $(document).ready(function() {
            $('#datepicker').datepicker({
                maxDate: new Date(),
                autoclose: true,
                dateFormat: 'dd/mm/yyyy',
                language: 'en'
            });
        });
    </script>
@endpush

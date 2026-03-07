@extends('admin.layouts.master')

<style>
    .print-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 36px;
        height: 36px;
        background-color: #28a745;
        /* green */
        color: #fff;
        border: none;
        border-radius: 10%;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .print-btn:hover {
        background-color: #218838;
        /* darker green */
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .print-btn i {
        pointer-events: none;
    }
</style>
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Booking Number')</th>
                                <th class="text-center">@lang('Cattle Name/number')</th>
                                <th class="text-center">@lang('Payment Date')</th>
                                <th class="text-center">@lang('Payment Amount')</th>
                                {{-- <th class="text-center">@lang('Payment Method')</th> --}}
                            </tr>
                        </thead>

                        <tbody class="table-border-bottom-0">
                            @forelse ($bookingPayments as $bookingNumber => $payments)
                                <tr style="background-color: #f8f9fa; font-weight: bold;">
                                    <td colspan="6" class="text-left">
                                        @lang('Booking Number'): <a href="#" class="text-primary">{{ $bookingNumber }}</a>
                                    </td>
                                </tr>
                                @foreach ($payments as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        <td class="text-center">
                                            @if ($item->booking)
                                                <a href="{{ route('admin.booking.payment.list', $item->booking->id) }}" class="text-primary">
                                                    {{ $item->booking->booking_number }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $item->cattle_name ?? 'N/A' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($item->payment_date)
                                                {{ \Carbon\Carbon::parse($item->payment_date)->format('Y-m-d') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($item->price)
                                                {{ showAmount($item->price) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        {{-- <td class="text-center">
                                            @if ($item->booking && $item->booking->payment_method)
                                                {{ ucfirst($item->booking->payment_method) }}
                                            @else
                                                N/A
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                                <tr style="background-color: #e8f5e9; font-weight: bold;">
                                    <td colspan="3" class="text-right">@lang('Group Total'):</td>
                                    <td class="text-center">
                                        {{ showAmount($payments->sum('price')) }}
                                    </td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <button type="button" onclick="history.back()" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </button>
@endpush

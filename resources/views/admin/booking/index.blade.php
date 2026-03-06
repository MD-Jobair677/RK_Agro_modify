@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.booking.create') }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Booking Number')</th>
                                <th class="text-center">@lang('Customer Name')</th>
                                <th class="text-center">@lang('Delivery Location')</th>
                                <th class="text-center">@lang('Price')</th>
                                <th class="text-center">@lang('Actions')</th>
                                <th class="text-center">@lang('status')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($bookings as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->booking_number }}</td>
                                    <td class="text-center">{{ $item->customer->fullname }}</td>
                                    <td class="text-center">{{ $item->delivery_location->district_city }}/
                                        @if (strlen(__($item->delivery_location->area)) > 20)
                                            {{ substr(__($item->delivery_location->area), 0, 35) . '...' }}
                                        @else
                                            {{ __($item->delivery_location->area) }}
                                        @endif
                                    <td class="text-center">
                                        {{ showAmount($item->bookingNumberGroupPrices($item->booking_number)) }}</td>
                                    @if ($item->status == 1)
                                        <td class="text-center">
                                            <div class="btn-group">



                                                @if ($item->booking_status == 'cancel')
                                                    <!-- Show Undo button if cancelled -->
                                                    <button class="btn btn-sm btn-warning undoBookingBtn"
                                                        data-id="{{ $item->id }}">
                                                        @lang('Undo')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-label-primary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                        {{ $item->booking_status == 'cancel' ? 'disabled' : '' }}>
                                                        @lang('Action')
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.booking.edit', $item->id) }}"
                                                                class="btn btn-sm btn-warning">
                                                                <span class="tf-icons las la-pen me-1"></span>
                                                                @lang('Edit')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.booking.view', $item->id) }}"
                                                                class="btn btn-sm btn-info"><span
                                                                    class="tf-icons las la-eye me-1"></span>
                                                                @lang('View')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.booking.payment.list', $item->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                <span class="tf-icons las la-money-bill-wave me-1"></span>
                                                                @lang('Payment')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="dropdown-item delete"
                                                                data-id="{{ $item->id }}">
                                                                <span class="las la-trash fs-6 link-danger"></span>
                                                                @lang('Delete')
                                                            </a>
                                                        </li>


                                                        <div>


                                                            <form id="delete-form-{{ $item->id }}"
                                                                action="{{ route('admin.booking.delete.booking', $item->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>


                                                        {{-- 
                                                    @php
                                                        dd($item->sale_price,$item->total_payment_amount);
                                                    @endphp --}}




                                                        @if ($item->sale_price < $item->total_payment_amount)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.refund.payment', $item->id) }}"
                                                                    class="btn btn-sm btn-info">
                                                                    <span
                                                                        class="tf-icons las la-money-bill-wave me-1"></span>
                                                                    @lang('Refund')
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($item->sale_price <= $item->total_payment_amount)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.delivery.print', $item->id) }}"
                                                                    class="btn btn-sm btn-warning">
                                                                    <span class="tf-icons las la-print me-1"></span>
                                                                    @lang('Print')
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endif

                                            </div>
                                        </td>
                                    @elseif($item->status == 3)
                                        <td class="text-center">
                                            <a href="{{ route('admin.booking.cattles.delivery', $item->id) }}"
                                                class="btn btn-sm btn-info">
                                                <span class="tf-icons las la-truck me-1"></span>
                                                @lang('Delivery')
                                            </a>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-success">
                                                @lang('Delivered')
                                            </a>
                                        </td>
                                    @endif


                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm 
    {{ $item->booking_status == 'cancel' ? 'btn-danger' : 'btn-success' }} 
    cancelBookingBtn"
                                            data-id="{{ $item->id }}">

                                            {{ $item->booking_status == 'cancel' ? __('Canceled') : __('Cancel') }}
                                        </button>

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

                @if ($bookings->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($bookings) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search by number or customer name..." dateSearch="no" />
@endpush

@push('page-script')
    <script>
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });



        $(document).ready(function() {
            $('.cancelBookingBtn').click(function(e) {
                e.preventDefault();

                var bookingId = $(this).data('id');
                var button = $(this);

                if (!confirm('Are you sure you want to cancel this booking?')) return;

                $.ajax({
                    url: "{{ route('admin.booking.cancel') }}",
                    method: 'POST',
                    data: {
                        booking_id: bookingId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            //     alert(res.message);
                            // console.log(res.message);

                            // Optional: change button text or color
                             location.reload();
                            
                        }
                    },
                    error: function(err) {
                        alert('Something went wrong!');
                    }
                });
            });
        });











        $(document).ready(function(){

    $('.undoBookingBtn').click(function(e){
        e.preventDefault();

        var button = $(this);
        var bookingId = button.data('id');

        if(!confirm('Are you sure you want to undo cancel?')) return;

        $.ajax({
            url: "{{ route('admin.booking.undo') }}",
            type: 'POST',
            data: {
                booking_id: bookingId,
                _token: "{{ csrf_token() }}"
            },
            success: function(res){
                if(res.success){
                 

                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function(err){
                alert('Something went wrong!');
            }
        });
    });

});
    </script>
@endpush

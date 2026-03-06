@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                
                    <button type="button" class="btn btn-sm btn-success detailBtn" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasBoth"
                        data-url="{{ route('admin.booking.payment.store', $cattleBooking->id) }}"
                        data-form-text-informations ='{"heading":"Create Payment","button_name":"Create"}'
                        data-booking-due-price="{{ showAmount($cattleBooking->sale_price - $bookingPayments->sum('price')) }}"
                        aria-controls="offcanvasBoth">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </button>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Price')</th>
                                <th class="text-center">@lang('Date')</th>
                                {{-- <th class="text-center">@lang('Actions')</th> --}}
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($bookingPayments as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ showAmount($item->price) }}</td>
                                    <td class="text-center">{{ showDateTime($item->created_at, 'd-m-Y h:i A') }}</td>
                                    {{-- <td class="text-center">
                                        <a href="{{ route('admin.booking.payment.index', $item->id) }}" class="btn btn-sm btn-info">
                                            <span class="tf-icons las la-money-bill-wave me-1"></span>
                                            @lang('Payments')
                                        </a>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($bookingPayments->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($bookingPayments) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth"
        aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h4 id="offcanvasBothLabel" class="offcanvas-title">@lang('Create Payment')</h4>
        </div>
        <div class="offcanvas-body my-auto mx-0">
            <div class="mb-4 border rounded p-3">

                <form id="offcanvasForm" class="card-body" action="#" method="POST">
                    @csrf
                    <div class="row">
                       
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label required">@lang('Payment Price')</label>
                                <div class="col-sm-12">
                                    <input type="number" min="0" step="0.01" class="form-control"
                                    step="any" name="price" 
                                    placeholder="@lang('Payment price fo cattle in BDT')">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-12 col-form-label ">@lang('Due Amount')</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="due_price" value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 sds">@lang('Create')</button>
                            <button type="reset" class="btn btn-label-secondary"
                                data-bs-dismiss="offcanvas">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.booking.view', strtolower($cattleBooking->booking_number)) }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush

@push('page-script')
    <script>
        document.querySelectorAll('.detailBtn').forEach(button => {
            button.addEventListener('click', function(event) {
                // find inside the offcanvas class and id
                const form = document.getElementById('offcanvasForm');
                const salePriceInput = document.getElementsByName('price')[0];
                const duePriceInput = document.getElementsByName('due_price')[0];
                const formHeading = document.getElementsByClassName('offcanvas-title')[0];
                const formButton = form.querySelector('button[type="submit"]');
                form.reset();

                // Example: Fetch and display data from the button attributes
                let url = this.getAttribute('data-url');
                let formTextInformations = JSON.parse(this.getAttribute('data-form-text-informations'));
                let duePrice = parseFloat(this.getAttribute('data-booking-due-price') || '0');
        

                // Optionally, set this information inside the offcanvas
                form.setAttribute('action', url);
                formHeading.textContent = formTextInformations.heading;
                formButton.textContent = formTextInformations.button_name;
                
                duePriceInput.value = duePrice.toFixed(2);
                
            });
        });
    </script>
@endpush

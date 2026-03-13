@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">@lang('Detailed Booking Report')</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-secondary">
                            <span class="tf-icons las la-arrow-left me-1"></span>
                            @lang('Back')
                        </a>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#columnSelectModal">
                            <span class="tf-icons las la-columns me-1"></span>
                            @lang('Select Columns')
                        </button>
                        <a href="{{ route('admin.booking.report.export', request()->query()) }}" class="btn btn-sm btn-success">
                            <span class="tf-icons las la-download me-1"></span>
                            @lang('Download Excel')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2">
                                <select name="booking_type" class="form-select form-select-sm">
                                    <option value="">@lang('All Booking Types')</option>
                                    <option value="1" {{ request('booking_type') == 1 ? 'selected' : '' }}>INS</option>
                                    <option value="2" {{ request('booking_type') == 2 ? 'selected' : '' }}>EID</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="@lang('Search by Booking Number')" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <span class="las la-search me-1"></span>@lang('Search')
                                </button>
                            </div>
                            @if(request('search') || request('from_booking') || request('to_booking') || request('booking_type'))
                                <div class="col-md-2">
                                    <a href="{{ route('admin.booking.report') }}" class="btn btn-secondary btn-sm">
                                        @lang('Clear')
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>

                    <form method="GET" class="mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="from_booking" class="form-control" placeholder="@lang('From Booking Number')" value="{{ request('from_booking') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_booking" class="form-control" placeholder="@lang('To Booking Number')" value="{{ request('to_booking') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <span class="las la-filter me-1"></span>@lang('Filter')
                                </button>
                            </div>
                            @if(request('from_booking') || request('to_booking'))
                                <div class="col-md-2">
                                    <a href="{{ route('admin.booking.report') }}" class="btn btn-secondary btn-sm">
                                        @lang('Clear')
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="card-body">
                    @forelse ($bookings as $booking)
                        <div class="mb-4 border rounded p-3 booking-section" style="background-color: #f8f9fa;">
                            <!-- Booking Header -->
                            <div class="booking-header mb-3">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; padding: 10px 0; border-bottom: 2px solid #e0e0e0;">
                                    <div style="flex: 1;">
                                        <span class="text-muted" style="font-size: 0.85rem; display: block; margin-bottom: 3px;">@lang('Booking Number')</span>
                                        <span style="font-weight: bold; font-size: 1rem;">{{ $booking->booking_number }}</span>
                                    </div>
                                    <div style="flex: 1;">
                                        <span class="text-muted" style="font-size: 0.85rem; display: block; margin-bottom: 3px;">@lang('Customer')</span>
                                        <div style="font-weight: bold; font-size: 1rem;">{{ $booking->customer->fullname ?? 'N/A' }}</div>
                                        <small style="color: #666; font-size: 0.85rem;">{{ $booking->customer->mobile ?? 'N/A' }}</small>
                                    </div>
                                    <div style="flex: 1;">
                                        <span class="text-muted" style="font-size: 0.85rem; display: block; margin-bottom: 3px;">@lang('Delivery Location')</span>
                                        <span style="font-weight: bold; font-size: 1rem;">
                                            @if ($booking->delivery_location)
                                                {{ $booking->delivery_location->district_city }}/{{ $booking->delivery_location->area }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div style="flex: 1;">
                                        <span class="text-muted" style="font-size: 0.85rem; display: block; margin-bottom: 3px;">@lang('Status')</span>
                                        @if ($booking->booking_status == 'cancel')
                                            <span class="badge bg-danger">@lang('Cancelled')</span>
                                        @elseif ($booking->booking_status == 'delivered')
                                            <span class="badge bg-success">@lang('Delivered')</span>
                                        @elseif ($booking->booking_status == 'pending')
                                            <span class="badge bg-warning">@lang('Pending')</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($booking->booking_status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Cattle Section -->
                            <div class="mb-3 cattle-section">
                                <h6 class="text-primary fw-bold mb-2">📦 @lang('Cattle Details')</h6>
                                @if ($booking->cattle_bookings->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered cattle-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center cattle-col-no" style="width: 5%">@lang('No')</th>
                                                    <th class="cattle-col-tag">@lang('Cattle Tag')</th>
                                                    <th class="cattle-col-name">@lang('Cattle Name')</th>
                                                    <th class="text-end cattle-col-saleprice">@lang('Sale Price')</th>
                                                    <th class="text-end cattle-col-advanceprice">@lang('Advance Price')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->cattle_bookings as $cattle)
                                                    <tr>
                                                        <td class="text-center cattle-col-no">{{ $loop->iteration }}</td>
                                                        <td class="cattle-col-tag">{{ $cattle->cattle->tag_number ?? 'N/A' }}</td>
                                                        <td class="cattle-col-name">{{ $cattle->cattle->name ?? 'N/A' }}</td>
                                                        <td class="text-end cattle-col-saleprice">{{ showAmount($cattle->sale_price) }}</td>
                                                        <td class="text-end cattle-col-advanceprice">{{ showAmount($cattle->advance_price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">@lang('No cattle found')</p>
                                @endif
                            </div>

                            <!-- Payment Section -->
                            <div class="mb-3 payment-section">
                                <h6 class="text-success fw-bold mb-2">💰 @lang('Payment Details')</h6>
                                @if ($booking->booking_payments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered payment-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center payment-col-no" style="width: 5%">@lang('No')</th>
                                                    <th class="payment-col-method">@lang('Payment Method')</th>
                                                    <th class="text-end payment-col-amount">@lang('Amount')</th>
                                                    <th class="payment-col-date">@lang('Payment Date')</th>
                                                    <th class="payment-col-cattle">@lang('Cattle/Note')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->booking_payments as $payment)
                                                    <tr>
                                                        <td class="text-center payment-col-no">{{ $loop->iteration }}</td>
                                                        <td class="payment-col-method">{{ $payment->payment_method ?? $payment->payment_method ?? 'N/A' }}</td>
                                                        <td class="text-end payment-col-amount">{{ showAmount($payment->price) }}</td>
                                                        <td class="payment-col-date">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                                                        <td class="payment-col-cattle">{{ $payment->cattle_name ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">@lang('No payments found')</p>
                                @endif
                            </div>

                            <!-- Summary -->
                            <div class="row border-top pt-2 mt-2 summary-section">
                                <div class="col-md-3">
                                    <small class="text-muted">@lang('Total Sale Price')</small>
                                    <p class="fw-bold text-dark">{{ showAmount($booking->sale_price) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">@lang('Total Paid')</small>
                                    <p class="fw-bold text-success">{{ showAmount($booking->total_payment_amount) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">@lang('Due Amount')</small>
                                    <p class="fw-bold text-danger">{{ showAmount($booking->due_price) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">@lang('Booking Date')</small>
                                    <p class="fw-bold">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            @lang('No bookings found')
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Column Select Modal -->
    <div class="modal fade" id="columnSelectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Select Columns to Display')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Cattle Columns -->
                        <div class="col-md-6">
                            <h6 class="mb-2 fw-bold">📦 @lang('Cattle Columns')</h6>
                            <div class="form-check">
                                <input class="form-check-input cattle-col-checkbox" type="checkbox" id="col_cattle_no" value="cattle-col-no" checked>
                                <label class="form-check-label" for="col_cattle_no">@lang('No')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input cattle-col-checkbox" type="checkbox" id="col_cattle_tag" value="cattle-col-tag" checked>
                                <label class="form-check-label" for="col_cattle_tag">@lang('Cattle Tag')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input cattle-col-checkbox" type="checkbox" id="col_cattle_name" value="cattle-col-name" checked>
                                <label class="form-check-label" for="col_cattle_name">@lang('Cattle Name')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input cattle-col-checkbox" type="checkbox" id="col_cattle_saleprice" value="cattle-col-saleprice" checked>
                                <label class="form-check-label" for="col_cattle_saleprice">@lang('Sale Price')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input cattle-col-checkbox" type="checkbox" id="col_cattle_advanceprice" value="cattle-col-advanceprice" checked>
                                <label class="form-check-label" for="col_cattle_advanceprice">@lang('Advance Price')</label>
                            </div>
                        </div>

                        <!-- Payment Columns -->
                        <div class="col-md-6">
                            <h6 class="mb-2 fw-bold">💰 @lang('Payment Columns')</h6>
                            <div class="form-check">
                                <input class="form-check-input payment-col-checkbox" type="checkbox" id="col_payment_no" value="payment-col-no" checked>
                                <label class="form-check-label" for="col_payment_no">@lang('No')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input payment-col-checkbox" type="checkbox" id="col_payment_method" value="payment-col-method" checked>
                                <label class="form-check-label" for="col_payment_method">@lang('Payment Method')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input payment-col-checkbox" type="checkbox" id="col_payment_amount" value="payment-col-amount" checked>
                                <label class="form-check-label" for="col_payment_amount">@lang('Amount')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input payment-col-checkbox" type="checkbox" id="col_payment_date" value="payment-col-date" checked>
                                <label class="form-check-label" for="col_payment_date">@lang('Payment Date')</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input payment-col-checkbox" type="checkbox" id="col_payment_cattle" value="payment-col-cattle" checked>
                                <label class="form-check-label" for="col_payment_cattle">@lang('Cattle/Note')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="button" class="btn btn-primary" onclick="applyColumnFilter()" data-bs-dismiss="modal">
                        @lang('Apply')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .card {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .mb-4 {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .btn-group, .card-header {
                display: none !important;
            }
        }

        .booking-section {
            display: block;
        }

        .booking-section.hide-header {
            display: none;
        }

        .booking-header {
            display: block;
        }

        .booking-header.hidden {
            display: none !important;
        }

        .cattle-section {
            display: block;
        }

        .cattle-section.hidden {
            display: none !important;
        }

        .payment-section {
            display: block;
        }

        .payment-section.hidden {
            display: none !important;
        }

        .summary-section {
            display: block;
        }

        .summary-section.hidden {
            display: none !important;
        }
    </style>

    <script>
        function updateExportLink() {
            // Get selected cattle columns
            const selectedCattleColumns = Array.from(document.querySelectorAll('.cattle-col-checkbox:checked'))
                .map(checkbox => checkbox.value);

            // Get selected payment columns
            const selectedPaymentColumns = Array.from(document.querySelectorAll('.payment-col-checkbox:checked'))
                .map(checkbox => checkbox.value);

            // Build query string
            let queryString = new URLSearchParams(window.location.search);
            queryString.delete('cattle_cols');
            queryString.delete('payment_cols');
            selectedCattleColumns.forEach(col => queryString.append('cattle_cols[]', col));
            selectedPaymentColumns.forEach(col => queryString.append('payment_cols[]', col));

            // Update export link
            const exportLink = document.querySelector('a[href*="report/export"]');
            if (exportLink) {
                exportLink.href = '{{ route("admin.booking.report.export") }}?' + queryString.toString();
            }
        }

        function applyColumnFilter() {
            // Get selected cattle columns
            const selectedCattleColumns = Array.from(document.querySelectorAll('.cattle-col-checkbox:checked'))
                .map(checkbox => checkbox.value);

            // Get selected payment columns
            const selectedPaymentColumns = Array.from(document.querySelectorAll('.payment-col-checkbox:checked'))
                .map(checkbox => checkbox.value);

            // Hide/Show cattle table columns
            document.querySelectorAll('.cattle-table th, .cattle-table td').forEach(cell => {
                const colClass = Array.from(cell.classList).find(cls => cls.startsWith('cattle-col-'));
                if (colClass && !selectedCattleColumns.includes(colClass)) {
                    cell.style.display = 'none';
                } else if (colClass) {
                    cell.style.display = '';
                }
            });

            // Hide/Show payment table columns
            document.querySelectorAll('.payment-table th, .payment-table td').forEach(cell => {
                const colClass = Array.from(cell.classList).find(cls => cls.startsWith('payment-col-'));
                if (colClass && !selectedPaymentColumns.includes(colClass)) {
                    cell.style.display = 'none';
                } else if (colClass) {
                    cell.style.display = '';
                }
            });

            // Update export link
            updateExportLink();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateExportLink();
        });
    </script>
@endsection

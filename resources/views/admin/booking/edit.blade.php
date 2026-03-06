@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.booking.update', $booking->id) }}"method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Cattle Booking Type')</label>
                                                <select class="select form-select" name="booking_type" required disabled>
                                                    <option>@lang('Select Booking Type')</option>
                                                    <option value="1"
                                                        {{ $booking->booking_type == 1 ? 'selected' : '' }}>
                                                        @lang('Instant booking')
                                                    </option>
                                                    <option value="2"
                                                        {{ $booking->booking_type == 2 ? 'selected' : '' }}>
                                                        @lang('Eid booking')
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Existing Customer Input --}}
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Customer')</label>
                                                <select class="select2 form-select" name="customer_id"
                                                    data-allow-clear="false" required disabled>
                                                    <option value="0"> @lang('Select customer')</option>
                                                    @foreach ($customers ?? [] as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $booking->customer_id == $item->id ? 'selected' : '' }}>
                                                            {{ ucfirst($item->fullname) }}
                                                            ({{ $item->phone }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Payment Method')</label>
                                                <input type="text" class="form-control" name="payment_method"
                                                    value="{{ $booking->payment_method }}" placeholder="@lang('Pay By')"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Delivery Date')</label>
                                                <input name="delivery_date" id="datepicker" type="text"
                                                    data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'
                                                    placeholder="@lang('Delivery date')" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="cattle-row-template d-none">
                                            <div class="cattle-row mb-3">
                                                <div class="row">
                                                    <div class="text-end">
                                                        <button type="button"
                                                            class="btn btn-danger remove-row">@lang('Delete')</button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label
                                                                class="col-form-label required">@lang('Cattle')</label>
                                                            <select class="select2 form-select cattle-select"
                                                                name="cattle_id" data-allow-clear="false">
                                                                <option value="">@lang('Select Cattle')</option>
                                                                @foreach ($cattles ?? [] as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ __($item->name) }}/{{ $item->tag_number }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('Cattle Sale Price')</label>
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control" step="any" name="sale_price"
                                                                value="{{ old('sale_price') }}"
                                                                placeholder="@lang('Total sale price fo cattle in BDT')">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('Weight On Delivery')</label>
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control" step="any"
                                                                name="weight_on_delivery" value="" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('Estimate Cost On Delivery')</label>
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control" step="any"
                                                                name="estimate_cost_on_delivery" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('Sale Advance Amount')</label>
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control" step="any" name="advance_price"
                                                                value="{{ old('price') }}"
                                                                placeholder="@lang('Total sale price fo cattle in BDT')">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dynamic Wrapper -->
                                        <div id="cattle-wrapper">
                                            <!-- Rows will be added here -->
                                        </div>
                                        <div class="col-lg-12 text-end">
                                            <button type="button" class="btn btn-primary"
                                                id="addCattle">@lang('Add More Cattle')</button>
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total Sale Amount')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="total_sale_price"
                                                    value="{{ $booking->sale_price }}" placeholder="@lang('Due price fo cattle in BDT')"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total Payment')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="total_payment_amount"
                                                    value="{{ $booking->total_payment_amount }}"
                                                    placeholder="@lang('Due price fo cattle in BDT')" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Due Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="due_price" value="{{ $booking->due_price }}"
                                                    placeholder="@lang('Due price fo cattle in BDT')" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 mb-3">
                                            <label class="col-form-label">@lang('Delivery Location')</label>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('District or City')</label>
                                                        <input type="text" class="form-control" name="distric_city"
                                                            value="{{ $booking->delivery_location->district_city }}"
                                                            placeholder="@lang('Enter Delivery District Or City')" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Area or Location')</label>
                                                        <input type="text" class="form-control" name="area_location"
                                                            value="{{ $booking->delivery_location->area }}"
                                                            placeholder="@lang('Enter Delivery Area or Location')" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Update')</button>
                                <a href=""></a>
                                <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Warning Modal -->
    <div class="modal fade" id="advanceWarningModal" tabindex="-1" aria-labelledby="advanceWarningLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="advanceWarningLabel">@lang('Warning')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Advance price cannot be greater than sale price. Please check your input.')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">@lang('Okay')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="notMatureModal" tabindex="-1" aria-labelledby="notMatureModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="notMatureModalLabel">@lang('Cattle Maturity Alert')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('This cattle is not mature.')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">@lang('Okay')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.booking.index') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.css') }}">
@endpush
@push('page-style')
    <style>
        .bordered-cattle {
            border: 2px dashed #ddd;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fdfdfd;
        }

        body {
            overflow-x: hidden;
        }

        /* Optional styling */
        .suggestion-box {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            margin-top: 2px;
        }




        .suggestion-box div {
            padding: 8px;
            cursor: pointer;
        }

        .suggestion-box div:hover {
            background: #f0f0f0;
        }

        .suggestion-box .loading {
            text-align: center;
            padding: 10px;
            color: #666;
            font-style: italic;
        }

        .suggestion-box .loading::after {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            margin-left: 8px;
            border: 2px solid #ccc;
            border-top-color: #333;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }


        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        ::-webkit-scrollbar {
            width: .5rem;
        }

        ::-webkit-scrollbar-track {
            background: #bdc3c7;
            border-radius: .75rem;
        }

        ::-webkit-scrollbar-thumb {
            background: #34495e;
            border-radius: .75rem;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2c3e50;
        }

        .container {
            max-width: 920px;
            margin: auto;
            padding: 1.25rem;
            border-radius: 10px;
            background: #ecf0f1;
        }

        .file-form {

            position: relative;
            padding: 2.5rem;
            border: 2px dashed blue;
        }

        .file-form:hover {
            border-color: green;
        }

        .file-form.highlight {
            border-color: green;
        }

        .drop-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
            text-align: center;
            padding-top: 2rem;
            font-weight: bold;
            color: #34495e;
        }

        .previewCard {
            margin-top: 20px;
        }

        #fileInput {
            display: none;
        }

        #uploadedImage {
            max-height: 750px;
            overflow-y: auto;
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
        }

        .d-none {
            display: none !important;
        }

        #executeBtn {
            background-color: #34495e;
        }

        #executeBtn:hover {
            background-color: #2c3e50;
        }

        #executeBtn:active {
            background-color: #2c3e50;
            transform: scale(1.1);
        }


        #clearAllBtn {
            color: #ecf0f1;
            background-color: #e74c3c;
        }

        #clearAllBtn:active {
            background-color: #c0392b;
            transform: scale(1.1);
        }

        #clearAllBtn:hover {
            background-color: #c0392b;
        }

        .image-content {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            border: 1px dashed #bdc3c7;
            border-radius: .25rem;
            margin: .70rem;
            padding: .25rem;
        }

        .image-wrapper {
            position: relative;
        }

        .image-wrapper img {
            transition: 1s;
            width: 150px;
        }

        .image-wrapper:hover img {
            filter: blur(2px);
        }

        .image-wrapper span {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 15px;
            background: red;
            padding: 10px;
            border-radius: 20%;
            line-height: 10px;
        }

        .image-wrapper:hover span {
            display: block;
        }


        .title {
            text-align: center;
            margin-top: 4rem;
            color: #34495e;
        }

        @media only screen and (max-width: 620px) {
            #uploadedImage {
                justify-content: center;
            }
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    {{-- <script>
        (function($) {
            "use strict";

            let cattleIndex = 0;
            const editCattles = @json($booking->cattle_bookings);

            function estimateCostOnDelivery(object) {
                const cattleId = $(object).val();
                const deliveryDate = $('input[name="delivery_date"]').val();

                if (cattleId && deliveryDate.trim()) {
                    $.ajax({
                        url: "{{ route('admin.booking.estimate.cost.on.delivery') }}",
                        method: 'GET',
                        data: {
                            id: cattleId,
                            deliveryDate: deliveryDate
                        },
                        success: function(response) {
                            if (response.status) {
                                const $row = $(object).closest('.cattle-row');
                                $row.find('[name$="[estimate_cost_on_delivery]"]').val(response
                                    .totalEtimateCostOnDelivery.toFixed(2));
                                $row.find('[name$="[weight_on_delivery]"]').val(response.totalEtimateWeight
                                    .toFixed(2));

                                // যদি আগেই না থাকে তাহলে সেট করো
                                if (!$row.find('[name$="[estimate_cost_on_delivery]"]').val()) {
                                    $row.find('[name$="[estimate_cost_on_delivery]"]').val(response
                                        .totalEtimateCostOnDelivery.toFixed(2));
                                }
                                if (!$row.find('[name$="[weight_on_delivery]"]').val()) {
                                    $row.find('[name$="[weight_on_delivery]"]').val(response
                                        .totalEtimateWeight.toFixed(2));
                                }

                                if (response.notMature == 2) {
                                    $('#notMatureModal').modal('show');
                                }
                            }
                        }
                    });
                }
            }

            function calculateDue() {
                let totalDue = 0;
                let warningShown = false;

                $('.cattle-row').each(function() {
                    const sale = parseFloat($(this).find('[name$="[sale_price]"]').val()) || 0;
                    const advance = parseFloat($(this).find('[name$="[advance_price]"]').val()) || 0;

                    if (advance > sale && !warningShown) {
                        $('#advanceWarningModal').modal('show');
                        warningShown = true;
                    }

                    const due = sale - advance;
                    if (due > 0) totalDue += due;
                });

                $('[name="due_price"]').val(totalDue.toFixed(2));
            }

            function initSelect2($select, $parent) {
                $select.select2({
                    dropdownParent: $parent,
                    placeholder: "@lang('Select Cattle')",
                    allowClear: true,
                    width: '100%'
                }).on('change', function() {
                    estimateCostOnDelivery(this);
                    updateCattleOptions();
                });
            }

            function updateCattleOptions() {
                $('select[name$="[cattle_id]"]').each(function() {
                    const $select = $(this);
                    const currentVal = $select.val();

                    // Get all selected values excluding the current one
                    const selectedValues = $('select[name$="[cattle_id]"]').not($select).map(function() {
                        return $(this).val();
                    }).get().filter(Boolean);

                    let allOptions = $select.data('all-options');

                    if (!allOptions) {
                        $select.data('all-options', $select.find('option').clone());
                        allOptions = $select.data('all-options');
                    }

                    // Destroy previous Select2 instance
                    $select.select2('destroy');

                    $select.empty();

                    allOptions.each(function() {
                        const val = $(this).attr('value');
                        if (!val || val === currentVal || !selectedValues.includes(val)) {
                            $select.append($(this).clone());
                        }
                    });

                    // Re-assign current value and re-initialize select2
                    $select.val(currentVal).select2();
                });
            }

            function createCattleRow(data = {}, isFirst = false) {
                const $template = $('.cattle-row-template .cattle-row').clone();

                // name attribute গুলো ঠিক করো
                $template.find('[name="cattle_id"]').attr('name', `cattles[${cattleIndex}][cattle_id]`);
                $template.find('[name="sale_price"]').attr('name', `cattles[${cattleIndex}][sale_price]`);
                $template.find('[name="advance_price"]').attr('name', `cattles[${cattleIndex}][advance_price]`).prop('readonly', true);;
                $template.find('[name="estimate_cost_on_delivery"]').attr('name',
                    `cattles[${cattleIndex}][estimate_cost_on_delivery]`);
                $template.find('[name="weight_on_delivery"]').attr('name',
                    `cattles[${cattleIndex}][weight_on_delivery]`);

                const $select = $template.find('select');

                // Option আছে কি না চেক করে value set করো
                if (data.cattle_id) {
                    if (!$select.find(`option[value="${data.cattle_id}"]`).length) {
                        $select.append(new Option("Selected Cattle", data.cattle_id, true, true));
                    } else {
                        $select.val(data.cattle_id);
                    }
                }

                // Select2 ইনিশিয়ালাইজ করো
                initSelect2($select, $template);


                // বাকি ইনপুটগুলোর value বসাও
                $template.find('[name$="[sale_price]"]').val(data.sale_price ?? '');
                $template.find('[name$="[advance_price]"]').val(data.advance_price ?? '');
                $template.find('[name$="[estimate_cost_on_delivery]"]').val(data.estimate_cost_on_delivery ?? '');
                $template.find('[name$="[weight_on_delivery]"]').val(data.weight_on_delivery ?? '');

                if (isFirst) {
                    $template.find('.remove-row').parent().remove();
                } else {
                    $template.addClass('bordered-cattle');
                }

                $('#cattle-wrapper').append($template);

                // sale এবং advance এর input event এ due calculate করো
                $template.find('[name$="[sale_price]"], [name$="[advance_price]"]').on('input', calculateDue);

                // এখন Ajax কল দিয়ে যদি delivery_date থাকে, তাহলে প্রত্যেক row এর জন্য estimate ও weight calculation করো,
                // page load এ থাকুক বা নতুন row এ যোগ করো

                if ($('input[name="delivery_date"]').val()) {
                    // data এ আগেই estimate_cost_on_delivery বা weight_on_delivery থাকলে বাদ দাও Ajax কল,
                    // না থাকলে Ajax করো
                    if (!data.estimate_cost_on_delivery || !data.weight_on_delivery) {
                        estimateCostOnDelivery($select[0]);
                    }
                }

                cattleIndex++;
                updateCattleOptions();
            }


            function initDatepicker() {
                $('#datepicker').datepicker({
                    minDate: new Date(),
                    autoclose: true,
                    dateFormat: 'dd/mm/yyyy',
                    language: 'en',
                    onSelect: function() {
                        $('select[name$="[cattle_id]"]').each(function() {
                            estimateCostOnDelivery(this);
                        });
                    }
                });
            }

            $(document).ready(function() {
                initDatepicker();
                $('input[name="delivery_date"]').val(
                    "{{ \Carbon\Carbon::parse($booking->delivery_date)->format('d/m/Y') }}");
                if (editCattles && editCattles.length > 0) {
                    editCattles.forEach((item, index) => {
                        createCattleRow(item, index === 0);
                    });
                } else {
                    createCattleRow({}, true);
                }

                // page load এ due calculate করো
                calculateDue();

                $('#addCattle').click(function() {
                    createCattleRow({}, false);
                });

                $(document).on('click', '.remove-row', function() {
                    $(this).closest('.cattle-row').remove();
                    calculateDue();
                    updateCattleOptions();
                });

                $('input[name="delivery_date"]').on('change', function() {
                    $('select[name$="[cattle_id]"]').each(function() {
                        estimateCostOnDelivery(this);
                    });
                });
            });

        })(jQuery);
    </script> --}}

    <script>
        (function($) {
            "use strict";

            let cattleIndex = 0;
            const editCattles = @json($booking->cattle_bookings);

            function estimateCostOnDelivery(object) {
                const cattleId = $(object).val();
                const deliveryDate = $('input[name="delivery_date"]').val();

                if (cattleId && deliveryDate.trim()) {
                    $.ajax({
                        url: "{{ route('admin.booking.estimate.cost.on.delivery') }}",
                        method: 'GET',
                        data: {
                            id: cattleId,
                            deliveryDate: deliveryDate
                        },
                        success: function(response) {
                            if (response.status) {
                                const $row = $(object).closest('.cattle-row');
                                $row.find('[name$="[estimate_cost_on_delivery]"]').val(response
                                    .totalEtimateCostOnDelivery.toFixed(2));
                                $row.find('[name$="[weight_on_delivery]"]').val(response.totalEtimateWeight
                                    .toFixed(2));

                                if (response.notMature == 2) {
                                    $('#notMatureModal').modal('show');
                                }
                            }
                        }
                    });
                }
            }

            function calculateDue() {
                let totalDue = 0;
                let warningShown = false;

                $('.cattle-row').each(function() {
                    const sale = parseFloat($(this).find('[name$="[sale_price]"]').val()) || 0;
                    const advance = parseFloat($(this).find('[name$="[advance_price]"]').val()) || 0;

                    if (advance > sale && !warningShown) {
                        $('#advanceWarningModal').modal('show');
                        warningShown = true;
                    }

                    const due = sale - advance;
                    if (due > 0) totalDue += due;
                });



                let due_price = $('[name="due_price"]').val() || 0;
                // console.log('Previous Due Price:', due_price);

                due_price = totalDue;
                // $('[name="due_price"]').val(due_price);
            }

            function initSelect2($select, $parent) {
                $select.select2({
                    dropdownParent: $parent,
                    placeholder: "@lang('Select Cattle')",
                    allowClear: true,
                    width: '100%'
                }).on('change', function() {
                    estimateCostOnDelivery(this);
                    updateCattleOptions();
                });
            }

            function updateCattleOptions() {
                $('select[name$="[cattle_id]"]').each(function() {
                    const $select = $(this);
                    const currentVal = $select.val();

                    const selectedValues = $('select[name$="[cattle_id]"]').not($select).map(function() {
                        return $(this).val();
                    }).get().filter(Boolean);

                    let allOptions = $select.data('all-options');

                    if (!allOptions) {
                        $select.data('all-options', $select.find('option').clone());
                        allOptions = $select.data('all-options');
                    }

                    $select.select2('destroy');
                    $select.empty();

                    allOptions.each(function() {
                        const val = $(this).attr('value');
                        if (!val || val === currentVal || !selectedValues.includes(val)) {
                            $select.append($(this).clone());
                        }
                    });

                    $select.val(currentVal).select2();
                });
            }

            function createCattleRow(data = {}, isFirst = false) {
                const $template = $('.cattle-row-template .cattle-row').clone();

                // name attribute গুলো ঠিক করো
                $template.find('[name="cattle_id"]').attr('name', `cattles[${cattleIndex}][cattle_id]`);
                $template.find('[name="sale_price"]').attr('name', `cattles[${cattleIndex}][sale_price]`);
                $template.find('[name="estimate_cost_on_delivery"]').attr('name',
                    `cattles[${cattleIndex}][estimate_cost_on_delivery]`);
                $template.find('[name="weight_on_delivery"]').attr('name',
                    `cattles[${cattleIndex}][weight_on_delivery]`);

                // Advance price field logic
                const $advanceInput = $template.find('[name="advance_price"]');
                if (data.advance_price !== undefined && data.advance_price !== null) {
                    $advanceInput
                        .attr('name', `cattles[${cattleIndex}][advance_price]`)
                        .val(data.advance_price)
                        .prop('disabled', true);
                } else {
                    $advanceInput.closest('.form-group, .form-row, .input-group, div').remove();
                }

                const $select = $template.find('select');

                if (data.cattle_id) {
                    if (!$select.find(`option[value="${data.cattle_id}"]`).length) {
                        $select.append(new Option("Selected Cattle", data.cattle_id, true, true));
                    } else {
                        $select.val(data.cattle_id);
                    }
                }

                initSelect2($select, $template);

                $template.find('[name$="[sale_price]"]').val(data.sale_price ?? '');
                $template.find('[name$="[estimate_cost_on_delivery]"]').val(data.estimate_cost_on_delivery ?? '');
                $template.find('[name$="[weight_on_delivery]"]').val(data.weight_on_delivery ?? '');

                if (isFirst) {
                    $template.find('.remove-row').parent().remove();
                } else {
                    $template.addClass('bordered-cattle');
                }

                $('#cattle-wrapper').append($template);

                $template.find('[name$="[sale_price]"], [name$="[advance_price]"]').on('input', calculateDue);

                if ($('input[name="delivery_date"]').val()) {
                    if (!data.estimate_cost_on_delivery || !data.weight_on_delivery) {
                        estimateCostOnDelivery($select[0]);
                    }
                }

                cattleIndex++;
                updateCattleOptions();
            }

            function initDatepicker() {
                $('#datepicker').datepicker({

                    autoclose: true,
                    dateFormat: 'dd/mm/yyyy',
                    language: 'en',
                    onSelect: function() {
                        $('select[name$="[cattle_id]"]').each(function() {
                            estimateCostOnDelivery(this);
                        });
                    }
                });
            }

            $(document).ready(function() {
                initDatepicker();
                $('input[name="delivery_date"]').val(
                    "{{ \Carbon\Carbon::parse($booking->delivery_date)->format('d/m/Y') }}"
                );

                if (editCattles && editCattles.length > 0) {
                    editCattles.forEach((item, index) => {
                        createCattleRow(item, index === 0);
                    });
                } else {
                    createCattleRow({}, true);
                }

                calculateDue();

                $('#addCattle').click(function() {
                    createCattleRow({}, false);
                });

                $(document).on('click', '.remove-row', function() {
                    $(this).closest('.cattle-row').remove();
                    calculateDue();
                    updateCattleOptions();
                });

                $('input[name="delivery_date"]').on('change', function() {
                    $('select[name$="[cattle_id]"]').each(function() {
                        estimateCostOnDelivery(this);
                    });
                });
            });

        })(jQuery);
    </script>
@endpush

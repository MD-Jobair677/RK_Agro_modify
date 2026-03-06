@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.booking.store') }}"method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Cattle Booking Type')</label>
                                                <select class="select form-select" name="booking_type" required>
                                                    <option>@lang('Select Booking Type')</option>
                                                    <option value="1">@lang('Instant booking')</option>
                                                    <option value="2">@lang('Eid booking')</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Existing Customer Input --}}
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Customer')</label>
                                                <select class="select2 form-select" name="customer_id"
                                                    data-allow-clear="false" required>
                                                    <option value="0"> @lang('Select customer')</option>
                                                    <option value="new_customer"> @lang('Create New Customer')</option>
                                                    @foreach ($customers ?? [] as $item)
                                                        <option value="{{ optional($item)->id }}">
                                                            {{ ucfirst(optional($item)->fullname) }}
                                                            ({{ optional($item)->phone }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- New Customer --}}
                                        <div class="col-lg-12 col-md-12 mb-3 newCustomer">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Customer Name')</label>
                                                        <input type="text" class="form-control" name="cus_name"
                                                            value="{{ old('cus_name') }}" placeholder="@lang('Enter Customer Name')"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Customer Company Name')</label>
                                                        <input type="text" class="form-control" name="cus_comp_name"
                                                            value="{{ old('cus_comp_name') }}"
                                                            placeholder="@lang('Enter Customer Company Name')" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Contact Number')</label>
                                                        <input type="number" class="form-control" name="contact_number"
                                                            value="{{ old('contact_number') }}"
                                                            placeholder="@lang('Enter Contact Number')" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class=" col-form-label">@lang('Customer Address')</label>
                                                        <textarea type="text" class="form-control" rows="5" cols="5" name="cus_address"
                                                            placeholder="@lang('Enter Customer Address')" required>{{ old('cus_address') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Refernce Name')</label>
                                                        <input type="text" class="form-control" name="ref_name"
                                                            value="{{ old('ref_name') }}" placeholder="@lang('Enter Refernce Name')"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Refernce Contact Number')</label>
                                                        <input type="number" class="form-control" name="ref_cont_number"
                                                            value="{{ old('ref_cont_number') }}"
                                                            placeholder="@lang('Enter Refernce Contact Number')" required>
                                                    </div>
                                                </div>
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
                                                    value="{{ old('payment_method') }}" placeholder="@lang('Pay By')"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Delivery Date')</label>
                                                <input name="delivery_date" id="datepicker" type="text"
                                                    data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'
                                                    placeholder="@lang('Delivery date')" value="{{ old('delivery_date') }}"
                                                    autocomplete="off">
                                            </div>


                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Booking Date')</label>
                                                <input name="booking_date" id="datepicker" type="text"
                                                    data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'
                                                    placeholder="@lang('Booking date')" value="{{ old('booking_date') }}"
                                                    autocomplete="off">
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
                                                                placeholder="@lang('Total sale price of cattle in BDT')">
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
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label class="col-form-label">@lang('Sale Advance Amount')</label>
                                                            <input type="number" min="0" step="0.01"
                                                                class="form-control" step="any" name="advance_price"
                                                                value="{{ old('price') }}"
                                                                placeholder="@lang('Total sale price of cattle in BDT')">
                                                        </div>
                                                    </div>

                                                      <div class="col-lg-6 col-md-6 mb-3">



                                         


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

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="total_price" value="{{ old('total_price') }}"
                                                    placeholder="@lang('Total price oF cattle in BDT')" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Due Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="due_price" value="{{ old('due_price') }}"
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
                                                            value="{{ old('distric_city') }}"
                                                            placeholder="@lang('Enter Delivery District Or City')" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-3">
                                                    <div class="form-group">
                                                        <label class="col-form-label required">@lang('Area or Location')</label>
                                                        <input type="text" class="form-control" name="area_location"
                                                            value="{{ old('area_location') }}"
                                                            placeholder="@lang('Enter Delivery Area or Location')" required>
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
                                <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Save')</button>
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
    <script>
        (function($) {
            "use strict";
            let cattleIndex = 0;

            function estimateCostOnDelivery(object) {
                let cattleId = $(object).val();
                let deliveryDate = $('input[name="delivery_date"]').val();

                if (cattleId && deliveryDate && deliveryDate.trim() !== "") {
                    $.ajax({
                        url: "{{ route('admin.booking.estimate.cost.on.delivery') }}",
                        method: 'GET',
                        data: {
                            id: cattleId,
                            deliveryDate: deliveryDate,
                        },
                        success: function(response) {
                            if (response.status) {
                                $(object).closest('.row')
                                    .find('input[name$="[estimate_cost_on_delivery]"]')
                                    .val((response.totalEtimateCostOnDelivery).toFixed(2));

                                $(object).closest('.row')
                                    .find('input[name$="[weight_on_delivery]"]')
                                    .val((response.totalEtimateWeight).toFixed(2));

                                if (response.notMature == 2) {
                                    $('#notMatureModal').modal('show');
                                }
                            }
                        }
                    });
                }
            }

            function triggerAllEstimateCost() {
                $('select[name$="[cattle_id]"]').each(function() {
                    estimateCostOnDelivery(this);
                });
            }

            function calculateDue() {
                let totalDue = 0;
                let totalSale = 0;
                let warningShown = false;

                $('.cattle-row').each(function() {
                    let $row = $(this);
                    let sale = parseFloat($row.find('[name$="[sale_price]"]').val()) || 0;
                    let advance = parseFloat($row.find('[name$="[advance_price]"]').val()) || 0;

                    if (advance > sale) {
                        if (!warningShown) {
                            $('#advanceWarningModal').modal('show');
                            warningShown = true;
                        }
                        return true;
                    }
                    totalSale += sale

                    let due = sale - advance;
                    if (due > 0) totalDue += due;
                });

                $('[name="due_price"]').val(totalDue.toFixed(2));
                $('[name="total_price"]').val(totalSale.toFixed(2));
            }

            function initSelect2(element) {
                element.select2({
                    placeholder: "@lang('Select Cattle')",
                    allowClear: true,
                    width: '100%'
                }).on('change', function() {
                    estimateCostOnDelivery(this);
                    updateCattleOptions();
                });
            }

            function updateCattleOptions() {
                let selectedValues = $('select[name$="[cattle_id]"]').map(function() {
                    return $(this).val();
                }).get().filter(Boolean); // Remove null/empty values

                $('select[name$="[cattle_id]"]').each(function() {
                    let $select = $(this);
                    let currentVal = $select.val();
                    let allOptions = $select.data('all-options');

                    if (!allOptions) {
                        $select.data('all-options', $select.find('option').clone());
                        allOptions = $select.data('all-options');
                    }

                    $select.empty();

                    allOptions.each(function() {
                        let val = $(this).attr('value');
                        if (!val || val === currentVal || !selectedValues.includes(val)) {
                            $select.append($(this).clone());
                        }
                    });

                    $select.val(currentVal).trigger('change.select2');
                });
            }

            function addCattleRow(isFirst = false) {
                let $template = $('.cattle-row-template .cattle-row').clone();

                $template.find('.cattle-select').removeClass('select2-hidden-accessible')
                    .next(".select2-container").remove();

                $template.find('[name="cattle_id"]').attr('name', `cattles[${cattleIndex}][cattle_id]`);
                $template.find('[name="sale_price"]').attr('name', `cattles[${cattleIndex}][sale_price]`);
                $template.find('[name="advance_price"]').attr('name', `cattles[${cattleIndex}][advance_price]`);
                $template.find('[name="estimate_cost_on_delivery"]').attr('name', `cattles[${cattleIndex}][estimate_cost_on_delivery]`);
                $template.find('[name="weight_on_delivery"]').attr('name',
                    `cattles[${cattleIndex}][weight_on_delivery]`);

                if (isFirst) {
                    $template.find('.remove-row').parent().remove();
                } else {
                    $template.addClass('bordered-cattle');
                }

                $template.find('input:enabled, select:enabled').attr('required', true);
                $('#cattle-wrapper').append($template);

                let $select = $template.find('.cattle-select');
                initSelect2($select);

                // Force no pre-selection (important fix)
                $select.val(null).trigger('change');

                // Bind due calculator
                $template.find('[name$="[sale_price]"], [name$="[advance_price]"]').on('input', calculateDue);

                if ($('input[name="delivery_date"]').val()) {
                    estimateCostOnDelivery($select[0]);
                }

                cattleIndex++;
                updateCattleOptions(); // Prevent selected duplicates
            }

            function toggleSupplierFields() {
                let selectedVal = $('select[name="customer_id"]').val();
                let isNew = selectedVal === "new_customer";

                let fields = [
                    'input[name="cus_name"]',
                    'input[name="cus_comp_name"]',
                    'input[name="contact_number"]',
                    'input[name="ref_name"]',
                    'input[name="ref_cont_number"]',
                    'textarea[name="cus_address"]'
                ];

                fields.forEach(selector => {
                    let $field = $(selector);
                    let $wrapper = $('.newCustomer');

                    if (isNew) {
                        $field.prop('required', true);
                        $wrapper.removeClass('d-none');
                    } else {
                        $field.prop('required', false);
                        $wrapper.addClass('d-none');
                    }
                });
            }

            function initDatepicker() {
                $('#datepicker').datepicker({
                    
                    autoclose: true,
                    dateFormat: 'dd/mm/yyyy',
                    language: 'en',
                    onSelect: function() {
                        triggerAllEstimateCost();
                    }
                });
            }
            function initDatepicker() {
                $('#datepicker2').datepicker({
                    
                    autoclose: true,
                    dateFormat: 'dd/mm/yyyy',
                    language: 'en',
                    onSelect: function() {
                        triggerAllEstimateCost();
                    }
                });
            }

            $(document).ready(function() {
                addCattleRow(true);
                initDatepicker();
                initSelect2($('.select2'));
                toggleSupplierFields();
                triggerAllEstimateCost();
                updateCattleOptions();

                $('#addCattle').click(function() {
                    addCattleRow(false);
                });

                $(document).on('click', '.remove-row', function() {
                    $(this).closest('.cattle-row').remove();
                    calculateDue();
                    updateCattleOptions();
                });

                $(document).on('input', '[name$="[sale_price]"], [name$="[advance_price]"]', function() {
                    calculateDue();
                });

                $('select[name="customer_id"]').on('change', function() {
                    toggleSupplierFields();
                });

                $('input[name="delivery_date"]').on('change', function() {
                    triggerAllEstimateCost();
                });
            });
        })(jQuery);
    </script>
@endpush

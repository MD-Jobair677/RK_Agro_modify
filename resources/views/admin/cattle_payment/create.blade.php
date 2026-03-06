@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.booking.payment.store',$cattleBooking->id) }}"method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group position-relative">
                                                <label class="col-form-label">@lang('Booking Number')</label>
                                                <input type="text" class="form-control" name="booking_number"
                                                    value="{{ old('booking_number') }}" placeholder="@lang('Booking number')">
                                                <div id="booking_suggestions" class="suggestion-box" style="display: none;"></div>
                                            </div>

                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Customer')</label>
                                                <select class="select2 form-select" name="customer_id"
                                                    data-allow-clear="false" required>
                                                    @foreach ($customers ?? [] as $item)
                                                        <option value="{{ $item->id }}">{{ __($item->fullname) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group ">
                                                <label class="col-form-label required">@lang('Cattle')</label>
                                                <select class="select2 form-select" name="cattle_id"
                                                    data-allow-clear="false" required>
                                                    @foreach ($cattles ?? [] as $item)
                                                        <option value="{{ $item->id }}">{{ __($item->name) }} /
                                                            {{ $item->tag_number }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Cattle Booking Type')</label>
                                                <select class="select form-select" name="booking_type" required>
                                                    <option value="1">@lang('Instant booking')</option>
                                                    <option value="2">@lang('Eid booking')</option>
                                                </select>
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
                                                <label class="col-form-label">@lang('Cattle Sale Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="sale_price" value="{{ old('sale_price') }}"
                                                    placeholder="@lang('Total sale price fo cattle in BDT')">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Sale Advance Amount')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="price" value="{{ old('price') }}"
                                                    placeholder="@lang('Total sale price fo cattle in BDT')">
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

                                        <div class="col-lg-12 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Booking Status')</label>
                                                <select class="select form-select" name="booking_status" required>
                                                    <option value="1">@lang('Pending')</option>
                                                    <option value="2">@lang('Delivered')</option>
                                                </select>
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
            $(document).ready(function() {

                // booking number ajax
                $(document).ready(function() {
                    $('input[name=booking_number]').on('keyup', function() {
                        let query = $(this).val();

                        if (query.length > 0) {

                            $('#booking_suggestions').html(
                                '<div class="loading">Searching...</div>').show();
                            $.ajax({
                                url: "{{ route('admin.booking.number.search') }}", // 🔁 route name
                                method: 'GET',
                                data: {
                                    search: query
                                },
                                success: function(response) {
                                    let suggestions = response.data || [];

                                    if (suggestions.length > 0) {
                                        let html = '';
                                        suggestions.forEach(item => {
                                            html += `<div class="suggestion-item" data-value="${item.booking_number}">${item.booking_number}
                                            </div>`;
                                        });
                                        $('#booking_suggestions').html(html).show();
                                    } else {
                                        $('#booking_suggestions').html(
                                            '<div class="text-center">No result found</div>'
                                        ).show();
                                        setTimeout(() => {
                                            $('#booking_suggestions').hide();
                                        }, 1500);
                                    }
                                },
                                error: function() {
                                    $('#booking_suggestions').html(
                                        '<div class="loading">Error loading suggestions</div>'
                                    ).show();
                                }
                            });
                        } else {
                            $('#booking_suggestions').hide();
                        }
                    });

                    // On click suggestion
                    $(document).on('click', '.suggestion-item', function() {
                        let bookingNumber = $(this).data('value');
                        $('input[name=booking_number]').val(bookingNumber);
                        $('#booking_suggestions').hide();
                    });

                    // Optional: hide when click outside
                    $(document).click(function(e) {
                        if (!$(e.target).closest('#booking_number, #booking_suggestions')
                            .length) {
                            $('#booking_suggestions').hide();
                        }
                    });
                });

                //end  booking number ajax




                function updateDuePrice() {
                    let salePrice = parseFloat($('input[name=sale_price]').val()) || 0;
                    let price = parseFloat($('input[name=price]').val()) || 0;
                    let duePrice = (parseFloat(salePrice) - parseFloat(price)).toFixed(2);
                    $('input[name=due_price] ').val(duePrice);
                }
                $('input[name=sale_price], input[name=price]').on('input', updateDuePrice);


                updateDuePrice(); // for use when document load then with existing Due Price calculation


                $('#datepicker').datepicker({
                    minDate: new Date(),
                    autoclose: true,
                    dateFormat: 'dd/mm/yyyy',
                    language: 'en'
                });
                $('.select2').select2({
                    placeholder: "@lang('Select value')",
                    dropdownParent: $('body')
                });
            });
        })(jQuery);
    </script>
@endpush

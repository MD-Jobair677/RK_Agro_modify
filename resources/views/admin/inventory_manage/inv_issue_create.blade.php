@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.inventory.issue.store') }}"method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            {{-- Item input fields --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Select Item')</label>
                                                <select class="form-select" name="item_id" required>
                                                    <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Select Warehouse')</label>
                                                <select class="form-select" name="warehouse_id" required>
                                                    <option value="{{ $warehouse->id }}">{{ __($warehouse->name) }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Issue Quantity')</label>
                                                <input type="number" class="form-control" name="issue_qnt" min="0" step="any"
                                                    value="{{ old('issue_qnt') }}" placeholder="@lang('Enter Issue Quantity')" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Issue Date')</label>
                                                <input name="issue_date" id="datepicker" type="text" data-range="false"
                                                    data-language="en" class="datepicker-here form-control"
                                                    data-position='bottom right' placeholder="@lang('Choose Issue date')"
                                                    autocomplete="off" value="{{ old('issue_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class=" col-form-label">@lang('Note')</label>
                                                <textarea type="text" class="form-control" rows="5" cols="5" name="note"
                                                    placeholder="@lang('Note')">{{ old('note') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class=" col-form-label">@lang('Remark')</label>
                                                <textarea type="text" class="form-control" rows="5" cols="5" name="remark"
                                                    placeholder="@lang('Enter Remark')">{{ old('remark') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class=" col-form-label">@lang('Issue Reference or Department')</label>
                                                <textarea type="text" class="form-control" rows="5" name="issue_ref" placeholder="@lang('Enter Reference or Department')">{{ old('issue_ref') }}</textarea>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.inventory.stock.index', $warehouse->name) }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.css') }}">
@endpush

@push('page-style')
    <style>
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

            // Function to show/hide new supplier inputs
            function toggleSupplierFields() {
                let selectedVal = $('select[name="supplier_id"]').val();
                let isNewSupplier = selectedVal === "new_supplier";

                let fields = [
                    'select[name="category_id"]',
                    'input[name="sup_name"]',
                    'input[name="contact_number"]',
                    'textarea[name="sup_address"]'
                ];

                fields.forEach(selector => {
                    let $field = $(selector);
                    let $wrapper = $('.newSupplier');

                    if (isNewSupplier) {
                        $field.prop('required', true);
                        $wrapper.removeClass('d-none');
                    } else {
                        $field.prop('required', false);
                        $wrapper.addClass('d-none');
                    }
                });
            }

            // Run on page load
            $(document).ready(function() {
                toggleSupplierFields(); // Initial check
            });

            // Run on change
            $('select[name="supplier_id"]').on('change', function() {
                toggleSupplierFields();
            });

        })(jQuery);
    </script>
    <script>
        (function($) {
            "use strict";
            $('#datepicker').datepicker({
                maxDate: new Date(),
                autoclose: true,
                dateFormat: 'dd/mm/yyyy',
                language: 'en'
            });
            $('.select2').select2({
                placeholder: "@lang('Select value')",
                dropdownParent: $('body')
            });

        })(jQuery);
    </script>
@endpush

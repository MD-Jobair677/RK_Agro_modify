@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.account.gen_expns.store') }}"method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        {{-- <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-sm-3 col-form-label required">@lang('Account Head')</label>
                                                <select class="form-select" name="acc_head" required>
                                                    @foreach ($accountHeads as $accountHead)
                                                        <option value="{{ $accountHead->id }}"
                                                            data-subheads="{{ $accountHead->subHeads }}">
                                                            {{ $accountHead->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-sm-12 col-form-label required">@lang('Account Sub Head')</label>
                                                <select class="form-select" name="acc_sub_head" required></select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-sm-3 col-form-label required">@lang('Expense Type')</label>
                                                <select class="form-select" name="expense_type" required>
                                                    <option value="3">@lang('General Expense')</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Account Head Name')</label>
                                                <input type="text" class="form-control" name="acc_head"
                                                    value="{{ old('acc_head') }}" placeholder="@lang('Enter Head Name')"
                                                    required>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Purpose')</label>
                                                <input type="text" class="form-control" name="expn_purpose"
                                                    value="{{ old('expn_purpose') }}" placeholder="@lang('Enter Purpose')"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Cost of Amount')</label>
                                                <input type="number" class="form-control" name="cost_of_amount"
                                                    step="any" value="{{ old('cost_of_amount') }}"
                                                    placeholder="@lang('Enter Cost Amount')" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Date of Expense')</label>
                                                <input type="text" class="form-control" id="datepicker"
                                                data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'  name="date_of_expense"
                                                    value="{{ old('date_of_expense') }}" placeholder="@lang('Enter Date')"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class=" col-form-label">@lang('Note/Description')</label>
                                                <textarea type="text" class="form-control" rows="5" cols="5" name="description"
                                                    placeholder="@lang('Note/Description')">{{ old('description') }}</textarea>
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

{{-- @push('breadcrumb')
    <a href="{{ route('admin.booking.index') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush --}}
@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
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
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        $(document).ready(function() {
            $('select[name="acc_head"]').on('change', function() {
                let subheads = $(this).find(':selected').data('subheads');
                let html = `<option  disabled selected>@lang('Select one')</option>`;
                $.each(subheads, function(id, subhead) {
                    html += `<option  value="${subhead.id}">${subhead.name}</option>`
                });
                $('[name=acc_sub_head]').html(html);
            });
        });
    </script>

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

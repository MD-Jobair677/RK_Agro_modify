@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <!-- Customer Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Customer Information</h5>
                    </div>

                    <div class="card-body">
                        <p><strong>Booking Number:</strong> {{ $booking->booking_number ?? 'N/A' }}</p>
                        <p><strong>Customer Name:</strong>
                            {{ trim(($booking->customer->first_name ?? '') . ' ' . ($booking->customer->last_name ?? '')) ?: $booking->customer->company_name }}
                        </p>
                        <p><strong>Company Name:</strong> {{ $booking->customer->company_name ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $booking->customer->phone ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $booking->customer->email ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $booking->customer->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- FORM START -->
                <form action="{{ route('admin.booking.cattle.print.print') }}" method="POST">
                    @csrf

                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white d-flex justify-content-between">
                            <h5 class="mb-0">Sales Details</h5>
                            <a href="javascript:window.print()" class="text-white">
                                <i class="las la-print"></i>
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th class="text-white">Tag Number</th>
                                        <th class="text-white">Cattle Name</th>
                                        <th class="text-white">Sale Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($booking->cattle_bookings as $cattle_booking)
                                        <tr>


                                            <input class="d-none" type="number" name="booking_id"
                                                value="{{ $booking->id }}">
                                            <input class="d-none" type="number" name="customer_id"
                                                value="{{ $booking->customer->id }}">
                                            <td class="text-center">
                                                <input type="checkbox" class="row-check" name="selected_cattles[]"
                                                    value="{{ $cattle_booking->cattle->id }}">
                                            </td>
                                            <td>{{ $cattle_booking->cattle->tag_number }}</td>
                                            <td>{{ $cattle_booking->cattle->name }}</td>
                                            <td>{{ $cattle_booking->sale_price }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Print</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
                <!-- FORM END -->

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
        $(document).ready(function() {
            $('#datepicker').datepicker({
                maxDate: new Date(),
                autoclose: true,
                dateFormat: 'dd/mm/yyyy',
                language: 'en'
            });
        });
        $('.select2').select2({
            placeholder: "@lang('Select value')",
            dropdownParent: $('body')
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropArea = document.getElementById("dropSection");
            const fileInput = document.getElementById("fileInput");
            const uploadedImages = document.getElementById("uploadedImage");

            let dataTransfer = new DataTransfer();

            function handleFiles(files) {
                [...files].forEach(file => {
                    dataTransfer.items.add(file);
                    previewFile(file);
                });

                fileInput.files = dataTransfer.files;
            }

            function previewFile(file) {
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function() {
                    let elem = document.createElement("div");
                    elem.classList.add("image-content");
                    elem.setAttribute("data-file", file.name);
                    elem.innerHTML = `
                        <div class="image-wrapper">
                            <img alt="${file.name}" src="${reader.result}">
                            <span class="remove-btn">
                                <i class="fa-regular fa-circle-xmark"></i>
                            </span>
                        </div>
                    `;
                    uploadedImages.appendChild(elem);
                    uploadedImages.classList.remove('d-none');


                    elem.querySelector(".remove-btn").addEventListener("click", function() {
                        removeFile(file.name, elem);
                    });
                }
            }

            function removeFile(fileName, element) {
                element.remove();


                let updatedFiles = new DataTransfer();
                [...fileInput.files].forEach(file => {
                    if (file.name !== fileName) {
                        updatedFiles.items.add(file);
                    }
                });

                fileInput.files = updatedFiles.files;
                dataTransfer = updatedFiles;

                if (uploadedImages.childElementCount === 0) {
                    uploadedImages.classList.add('d-none');
                }
            }

            function handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                let files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFiles(files);
                }
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }


            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
            });

            dropArea.addEventListener('drop', handleDrop, false);

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });
        });





        const selectAllCheckbox = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-check');

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Individual checkbox control
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = [...rowCheckboxes].every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    </script>
@endpush

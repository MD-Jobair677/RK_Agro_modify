@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" method="POST" action="{{ route('admin.cattle.update.askprice', $cattle->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8">
                                            <div class="form-group mb-3">
                                                <label class="form-label">@lang('Choose Date For Estimate Cost')</label>
                                                <input name="date_of_estimate" id="datepicker" type="text"
                                                    data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'
                                                    placeholder="@lang('Date For Estimate Cost')" autocomplete="off"
                                                    value="{{ old('date_of_estimate_cost') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label class="form-label"></label>
                                            <div class="col-lg-12 text-end mb-5">
                                                <button class="btn btn-primary cal_est_cost"
                                                    data-cattle_data="{{ $cattle }}"
                                                    data-gen_exp_data="{{ $specificGenTotalExpenses }}">@lang('Show Estimate Cost')</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Asking Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="ask_price" value="{{ $cattle->asking_price }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total Daily Expense')</label>
                                                <input type="text" class="form-control" name="total_daily_expense"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total General Cost')</label>
                                                <input type="text" class="form-control" name="total_gen_exp" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Medicine Cost')</label>
                                                <input type="text" class="form-control" name="total_med_cost" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Total Expense')</label>
                                                <input type="text" class="form-control" name="total_expense" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Purchase Weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="purchase_weight"
                                                    value="{{ $cattle->purchase_weight }}" placeholder="@lang('Enter your weight')"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Last Updated Weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="last_updated_weight" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Final Weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="final_weight" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Name')</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $cattle->name }}" placeholder="@lang('Enter your name')" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Category Name')</label>
                                                <select class="form-select" name="cattle_category_id" disabled>
                                                    <option>@lang('Select your category')</option>
                                                    @foreach ($cattleCategories ?? [] as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $cattle->cattle_category_id === $item->id ? 'selected' : '' }}>
                                                            {{ __($item->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Tag Number')</label>
                                                <input type="text" class="form-control" name="tag_number"
                                                    value="{{ $cattle->tag_number }}" placeholder="@lang('Enter your tag number')"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Purchase Date')</label>
                                                <input name="purchase_date" id="" type="text"
                                                    data-range="false" data-language="en"
                                                    class="datepicker-here form-control" data-position='bottom right'
                                                    placeholder="@lang('Purchase date')" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Row Number')</label>
                                                <input type="text" class="form-control" name="row_number"
                                                    step="any" value="{{ $cattle->row_number }}"
                                                    placeholder="@lang('Enter your row number')">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Stall Number')</label>
                                                <input type="text" class="form-control" name="stall_number"
                                                    value="{{ $cattle->stall_number }}" placeholder="@lang('Enter your stall number')">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Breed')</label>
                                                <input type="text" class="form-control" name="breed"
                                                    value="{{ $cattle->breed }}" placeholder="@lang('Enter your breed')">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Gender')</label>
                                                <select class="form-select" name="gender" required>
                                                    <option>@lang('Select your Gender')</option>
                                                    <option value="Male"
                                                        {{ old('gender', $cattle->gender ?? '') == 'Male' ? 'selected' : '' }}>
                                                        @lang('Male')</option>
                                                    <option value="Female"
                                                        {{ old('gender', $cattle->gender ?? '') == 'Female' ? 'selected' : '' }}>
                                                        @lang('Female')</option>
                                                    <option value="Other"
                                                        {{ old('gender', $cattle->gender ?? '') == 'Other' ? 'selected' : '' }}>
                                                        @lang('Other')</option>
                                                    <option value="Unknown"
                                                        {{ old('gender', $cattle->gender ?? '') == 'Unknown' ? 'selected' : '' }}>
                                                        @lang('Unknown')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="col-sm-3 col-form-label required">@lang('Status')</label>
                                                <div class="col-sm-12">
                                                    <select class="form-select" name="status" required>
                                                        <option value="1"
                                                            {{ $cattle->status == 1 ? 'selected' : '' }}>@lang('Active')
                                                        </option>
                                                        <option value="0"
                                                            {{ $cattle->status == 0 ? 'selected' : '' }}>@lang('Inactive')
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label class=" col-form-label">@lang('Description')</label>
                                                <textarea type="text" class="form-control" rows="5" cols="5" name="description"
                                                    placeholder="@lang('Enter your description')">{{ $cattle->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <div class="container card">
                                                    <div class="file-form" id="dropSection">
                                                        <input id="fileInput" type="file" name="images[]" multiple
                                                            accept="image/*">
                                                        <label class="drop-content" for="fileInput">
                                                            @lang('Drop files to attach, or click to select')
                                                        </label>
                                                    </div>
                                                    <div class="card previewCard">
                                                        <div id="uploadedImage">
                                                            @foreach ($cattle->cattle_images ?? [] as $img)
                                                                <div class="image-wrapper pe-2">
                                                                    <img alt="@lang('listing-image')"
                                                                        src="{{ getImage(getFilePath('cattle') . '/' . $img->image_path) }}">
                                                                    <span class="remove-btn decisionBtn"
                                                                        data-action="{{ route('admin.cattle.image.delete', $img->id) }}"
                                                                        data-question="@lang('Are you sure to delete this listing image?')">
                                                                        <i class="las la-times-circle"></i>

                                                                    </span>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 my-3">
                                            <h3 class="bg-info text-white ps-4 py-2">@lang('Cattle Record Info')</h3>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Price for weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="price_for_weight"
                                                    value="{{ $cattle->primaryCattleRecord->price_for_weight }}"
                                                    placeholder="@lang('Total price for a specific weight in BDT')" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Weight for price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="weight_for_price"
                                                    value="{{ $cattle->primaryCattleRecord->weight_for_price }}"
                                                    placeholder="@lang('Total weight for a specific price in kg')" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Growth weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="growth_weight"
                                                    value="{{ $cattle->primaryCattleRecord->growth_weight }}"
                                                    placeholder="@lang('Growth weight of the cattle in kg or gm')" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Update Asking Price')</button>
                                <a href=""></a>
                                {{-- <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.cattle.index') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
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
            $('#datepicker').datepicker({
                minDate: new Date(),
                autoclose: true,
                dateFormat: 'dd/mm/yyyy',
                language: 'en'
            });
        });


        $('.cal_est_cost').on('click', function(e) {

            // const cattleData = data.val('cattle_data');
            const COW_GROUP    = 1;
            const GOAT_GROUP   = 2;
            const GEN_EXPN     = 3;
            const MEDICINE     = 4;
            let cattleData     = $(this).data('cattle_data');
            let genExpnObj     = $(this).data('gen_exp_data');
            let cattleCategory = cattleData.cattle_category;
            let purchasePrice  = parseFloat(cattleData.purchase_price);
            let latestWeight   = parseFloat(cattleData.last_cattle_record.last_updated_weight);
            let growthWeight   = parseFloat(cattleData.last_cattle_record.growth_weight);
            let ratioWeight    = parseFloat(cattleData.last_cattle_record.weight_for_price);
            let ratioPrice     = parseFloat(cattleData.last_cattle_record.price_for_weight);


            let totalGrowthWeight  = 0;
            let totalUpdatedWeight = 0;
            let totalRatioVal      = 0;
            let totalDailyExp      = 0;
            let medicine           = 0;      
            let genExpense         = 0;
            let grandTotal         = 0;


            const validFromDate = new Date(cattleData.last_cattle_record.valid_from_date);
            e.preventDefault(); // prevent form submission for testing

            const selectedDateStr = $('#datepicker').val(); // get string like "04/06/2025"
            if (!selectedDateStr) {
                alert("Please select a date.");
                return;
            }

            // Convert "dd/mm/yyyy" to Date
            const parts = selectedDateStr.split('/');
            const selectedDate = new Date(parts[2], parts[1] - 1, parts[0]); // yyyy, mm-1, dd

            // Calculate difference
            const timeDiff = selectedDate - validFromDate;
            const dayDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)); // convert ms to days

           console.log(dayDiff);
           

            // alert(`Total days: ${dayDiff}`);

            totalGrowthWeight  = dayDiff * growthWeight;
            totalUpdatedWeight = totalGrowthWeight + latestWeight;
            totalRatioVal      = (totalUpdatedWeight / ratioWeight) * ratioPrice;
            totalDailyExp      = totalRatioVal * dayDiff;

            if (cattleCategory.cattle_group == COW_GROUP) {
                genExpnObj.forEach(function(item) {
                    if (item.expens_type == GEN_EXPN) {
                        genExpense = parseFloat(item.per_cattle_expense);
                    } else {
                        medicine   = parseFloat(item.per_cattle_expense);
                    }
                });
            } else {
                medicine = 500;
                genExpense = 500;
                 
            }
            grandTotal = purchasePrice + totalDailyExp + medicine + genExpense;
            // console.log('gen exp '+genExpense);
            // console.log('medicine '+medicine);

            // console.log(dayDiff, totalGrowthWeight, latestWeight, totalUpdatedWeight, totalDailyExp);
            $('[name="total_daily_expense"]').val(totalDailyExp.toFixed(2));
            $('[name="total_gen_exp"]').val(genExpense.toFixed(2));
            $('[name="total_med_cost"]').val(medicine.toFixed(2));
            $('[name="total_expense"]').val(grandTotal.toFixed(2));
            $('[name="last_updated_weight"]').val(latestWeight.toFixed(2));
            $('[name="final_weight"]').val(totalUpdatedWeight.toFixed(2));
            // You can now use `dayDiff` for your estimate cost logic
        });
    </script>
    <script>
        $(document).ready(function() {

            const getDate = $('input[name=purchase_date]');

            const rawDate = "{{ $cattle->purchase_date }}";
            const date = new Date(rawDate.replace(' ', 'T')); // convert to ISO-compatible format

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // JS month is 0-indexed
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            const formatted = `${day}/${month}/${year}`;
            getDate.val(formatted);

        });
    </script>

    <script>
        const uploadedImage = document.getElementById('uploadedImage');

        if (uploadedImage.children.length === 0) {
            uploadedImage.classList.add('d-none');
        }
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
    </script>
@endpush

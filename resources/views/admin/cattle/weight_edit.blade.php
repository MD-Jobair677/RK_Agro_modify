@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.cattle.update_weight', $cattle->id) }}"method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Name')</label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $cattle->name }}" placeholder="@lang('Enter your name')" disabled
                                                    required>
                                            </div>
                                        </div>

                                          <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Purchase Weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="purchase_weight"
                                                    value="{{ $cattle->purchase_weight }}" placeholder="@lang('Enter your weight')"
                                                    required {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3 mt-1">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Purchase or Born Date')</label>
                                                <input name="purchase_date" id="" type="text" data-range="false"
                                                    data-language="en" class="datepicker-here form-control"
                                                    data-position='bottom right' placeholder="@lang('Purchase or Born Date')"
                                                    autocomplete="off" {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
                                            </div>
                                        </div>
                                      
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label required">@lang('Purchase Price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="purchase_price"
                                                    value="{{ $cattle->purchase_price }}" placeholder="@lang('Enter your price')"
                                                    required {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
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
                                                    value="{{ $cattle->lastCattleRecord->price_for_weight }}"
                                                    placeholder="@lang('Total price for a specific weight in BDT')" {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Weight for price')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="weight_for_price"
                                                    value="{{ $cattle->lastCattleRecord->weight_for_price }}"
                                                    placeholder="@lang('Total weight for a specific price in kg')" {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('Growth weight')</label>
                                                <input type="number" min="0" step="0.01" class="form-control"
                                                    step="any" name="growth_weight"
                                                    value="{{ $cattle->lastCattleRecord->growth_weight }}"
                                                    placeholder="@lang('Growth weight of the cattle in kg or gm')" {{($cattle->type == 1) || ($cattle->type == 2 && $hasOneYearPassed) ? '':'disabled'}}>
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
@endsection

@push('breadcrumb')
    <a href="{{ route('admin.cattle.index') }}" class="btn btn-label-primary">
        <span class="tf-icons las la-arrow-circle-left me-1"></span> @lang('Back')
    </a>
@endpush
@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush


@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
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
@endpush

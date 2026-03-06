<div class="modal fade" id="formGenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">@lang('Generate Form')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <form class="{{ $formClassName ?? 'generate-form' }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="update_id" value="">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">@lang('Form Type')</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="form_type" required>
                                <option value="">@lang('Select One')</option>
                                <option value="text">@lang('Text')</option>
                                <option value="textarea">@lang('Textarea')</option>
                                <option value="select">@lang('Select')</option>
                                <option value="checkbox">@lang('Checkbox')</option>
                                <option value="radio">@lang('Radio')</option>
                                <option value="file">@lang('File')</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">@lang('Is Required')</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="is_required" required>
                                <option value="">@lang('Select One')</option>
                                <option value="required">@lang('Required')</option>
                                <option value="optional">@lang('Optional')</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label required">@lang('Form Label')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="form_label" required>
                        </div>
                    </div>

                    <div class="extra_area"></div>
                </div>
                <hr>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary generatorSubmit">@lang('Add')</button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.js') }}"></script>
    <script src="{{ asset('assets/universal/js/form_generator.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $(document).find('.select2').select2({
                dropdownParent: '.select2-design'
            });
        })(jQuery);
    </script>
@endpush

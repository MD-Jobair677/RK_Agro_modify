<div class="col-12 mt-4">
    <div class="card mb-4 border">
        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom">
            <h5 class="card-header">{{__($formHeading) }}</h5>
            <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center me-4">
                <button type="button" class="btn btn-label-primary form-generate-btn">
                    <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4 addedField">
                @if ($form)
                    @foreach($form->form_data as $formData)
                        <div class="col-xxl-4 col-md-6">
                            <div class="card border p-3" id="{{ $loop->index }}">
                                <input type="hidden" name="form_generator[is_required][]" value="{{ $formData->is_required }}">
                                <input type="hidden" name="form_generator[extensions][]" value="{{ $formData->extensions }}">
                                <input type="hidden" name="form_generator[options][]" value="{{ implode(',', $formData->options) }}">

                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">@lang('Label')</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="form_generator[form_label][]" value="{{ $formData->name }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">@lang('Type')</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="form_generator[form_type][]" value="{{ $formData->type }}" readonly>
                                    </div>
                                </div>

                                @php
                                    $jsonData = json_encode([
                                        'type'        => $formData->type,
                                        'is_required' => $formData->is_required,
                                        'label'       => $formData->name,
                                        'extensions'  => explode(',', $formData->extensions) ?? 'null',
                                        'options'     => $formData->options,
                                        'old_id'      => '',
                                    ]);
                                @endphp

                                <div class="btn-group">
                                    <button type="button" class="btn btn-label-primary editFormData" data-form_item="{{ $jsonData }}" data-update_id="{{ $loop->index }}">
                                        <span class="tf-icons las la-pen me-1"></span></i> @lang('Edit')
                                    </button>
                                    <button type="button" class="btn btn-label-warning removeFormData">
                                        <i class="las la-trash me-1"></i> @lang('Delete')
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@push('page-script')
    <script>
        "use strict";

        var formGenerator = new FormGenerator();
        formGenerator.totalField = {{ $form ? count((array) $form->form_data) : 0 }}
    </script>

    <script src="{{ asset('assets/universal/js/form_actions.js') }}"></script>
@endpush

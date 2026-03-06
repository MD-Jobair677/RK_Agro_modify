@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Key')</th>
                                <th>{{ __($language->name) }}</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($json as $key => $value)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $value }}</td>
                                    <td>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-primary editBtn me-1" data-key="{{ $key }}" data-value="{{ $value }}">
                                                <span class="tf-icons las la-pen me-1"></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-label-danger deleteBtn" data-key="{{ $key }}" data-value="{{ $value }}">
                                                <span class="tf-icons las la-trash me-1"></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer pagination justify-content-center">
                    @php echo paginateLinks($json); @endphp
                </div>
            </div>
        </div>
    </div>

    {{-- Import Keywords Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Import Keywords')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label required">@lang('Import From')</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="select_lang" required>
                                <option value="">@lang('Select One')</option>
                                <option value="999">@lang('System')</option>

                                @foreach ($allLang as $lang)
                                    @if ($lang->id != $language->id)
                                        <option value="{{ $lang->id }}">{{ __($lang->name) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary importLang">@lang('Import')</button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Add New Keyword')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.language.store.key', $language->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Key')</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="key" value="{{ old('key') }}" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label required">@lang('Value')</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="value" value="{{ old('value') }}" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Update Keyword')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.language.update.key', $language->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-sm-3 col-form-label formHeading required"></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="value" rows="2" required></textarea>
                            </div>
                            <input type="hidden" name="key">
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal-onboarding modal fade animate__animated" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="onboarding-media">
                        <div class="mx-2">
                            <img src="{{ asset('assets/admin/images/light.png') }}" alt="light" width="100" class="img-fluid">
                        </div>
                    </div>
                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body">@lang('Make Your Decision')</h4>
                        <div class="onboarding-info">@lang('Are you confirm to remove this key from the current language?')</div>
                    </div>
                </div>
                <form action="{{ route('admin.language.delete.key', $language->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="key">
                    <input type="hidden" name="value">

                    <div class="modal-footer border-0 justify-content-center">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <form action="" method="GET" class="d-flex flex-wrap gap-2">
        <div class="d-inline">
            <div class="input-group justify-content-end">
                <input type="search" name="search" class="form-control" placeholder="@lang('Search by key')" value="{{ request()->search }}">
                <button class="btn btn-label-primary input-group-text" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
    </button>
    <button type="button" class="btn btn-label-info" data-bs-toggle="modal" data-bs-target="#importModal">
        <span class="tf-icons las la-file-upload me-1"></span> @lang('Import')
    </button>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.importLang').on('click', function(e) {
                var id = $('[name=select_lang]').val();

                if (id == '') {
                    showToasts('error', 'Invalid selection');
                    return 0;
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.language.import.lang') }}",
                        data: {
                            id: id,
                            toLangId: "{{ $language->id }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data == 'success') {
                                showToasts('success', 'Import keywords success');
                                window.location.href = "{{ url()->current() }}"
                            }
                        }
                    });
                }
            });

            $('.editBtn').on('click', function() {
                let modal = $('#editModal');

                modal.find('.formHeading').text($(this).data('key'));
                modal.find('[name=key]').val($(this).data('key'));
                modal.find('[name=value]').val($(this).data('value'));
                modal.modal('show');
            });

            $('.deleteBtn').on('click', function() {
                let modal = $('#deleteModal');

                modal.find('[name=key]').val($(this).data('key'));
                modal.find('[name=value]').val($(this).data('value'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

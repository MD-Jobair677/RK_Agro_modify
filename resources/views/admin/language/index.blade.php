@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card-body">
                <div class="alert alert-primary" role="alert">
                    @lang('When adding a new keyword, ensure it\'s entered precisely, with no extra spaces, as it will only apply to the chosen language.')
                </div>
                <div class="row g-4">
                    @foreach($languages as $language)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row gy-3 align-items-center">
                                        <div class="col-sm-7">
                                            <span class="d-flex align-items-center">
                                                <span class="me-2">
                                                    <i class="las la-folder-open fs-5 me-2 link-primary"></i>
                                                </span>
                                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                                    <b>{{ __($language->name) }} - {{ __($language->code) }}</b>
                                                    <div class="card-header-elements">@php echo $language->statusBadge @endphp</div>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="d-flex justify-content-sm-end justify-content-center">
                                                <div class="btn-group">
                                                    <button class="btn btn-label-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">@lang('Action')</button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button type="button" class="dropdown-item editBtn" data-resource="{{ $language }}">
                                                                <i class="las la-edit fs-5 link-primary"></i> @lang('Edit')
                                                            </button>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.language.translate.keyword', $language->id) }}">
                                                                <i class="las la-language fs-5 link-info"></i> @lang('Translate')
                                                            </a>
                                                        </li>

                                                        <li>
                                                            @if ($language->status)
                                                                <button class="dropdown-item decisionBtn" data-question="@lang('Are you confirming the inactivation of this language?')" data-action="{{ route('admin.language.status', $language->id) }}">
                                                                    <i class="las la-ban fs-5 link-warning"></i> @lang('Inactive')
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item decisionBtn" data-question="@lang('Are you confirming the activation of this language?')" data-action="{{ route('admin.language.status', $language->id) }}">
                                                                    <i class="las la-check-circle fs-5 link-success"></i> @lang('Active')
                                                                </button>
                                                            @endif
                                                        </li>

                                                        @if ($language->id != 1)
                                                            <li>
                                                                <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming the removal of this language from the system?')" data-action="{{ route('admin.language.delete', $language->id) }}">
                                                                    <i class="las la-trash-alt fs-5 link-danger"></i> @lang('Delete')
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Add New Language')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.language.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Name')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" placeholder="@lang('Portuguese')" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label required">@lang('Code')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="code" placeholder="@lang('pr')" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Add')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Update Language')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-sm-3 col-form-label required">@lang('Name')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" placeholder="@lang('Portuguese')" required>
                            </div>
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

    {{-- Keyword Modal --}}
    <div class="modal fade" id="keywordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFullTitle">@lang('All Keywords')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <div class="modal-body">
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        @lang('Most language keywords are here, but some may be missing due to database variations. You can manually add or import them from any language\'s translate page.')
                    </div>
                    <div class="form-group copy-texts-wrapper position-relative">
                        <div class="copyTexts">
                            <span class="copy">@lang('Copy')</span>
                        </div>
                        <textarea class="form-control langKeys key-added" id="langKeys" rows="30" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
    </button>
    <button type="button" class="btn btn-label-info keywordBtn" data-bs-toggle="modal" data-bs-target="#keywordModal">
        <span class="tf-icons las la-file-code me-1"></span> @lang('Keywords')
    </button>
@endpush

@push('page-style')
    <style>
        .copy-texts-wrapper:hover .copyTexts {
            visibility: visible;
            opacity: 1;
        }

        .copyTexts {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 99;
            background: #0000004d;
            width: 99%;
            height: 100%;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: .3s;
            cursor: pointer;
        }

        .copyTexts .copy {
            color: #fff;
            font-size: 40px;
            border-radius: 5px;
            background-color: transparent;
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.editBtn').on('click', function() {
                let modal = $('#editModal');
                let resource = $(this).data().resource;

                modal.find('[name=name]').val(resource.name);
                modal.find('form').attr('action', `${ '{{ route('admin.language.store') }}' }/${resource.id}`);

                modal.modal('show');
            });

            $('.keywordBtn').click(function(e) {
                e.preventDefault();
                $.get("{{ route('admin.language.keywords') }}", {}, function(data) {
                    $('.langKeys').text(data);
                });
            });

            $('.copyTexts').click(function() {
                var copyText = document.getElementById("langKeys");
                copyText.select();
                document.execCommand("copy");
                $('.copy').text('Copied');
                setTimeout(() => {
                    $('.copy').text('Copy');
                }, 2000);
            });
        })(jQuery);
    </script>
@endpush

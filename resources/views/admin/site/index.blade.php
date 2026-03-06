@extends('admin.layouts.master')

@section('master')
    @if (@$section->content)
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <form class="card-body" action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="content">

                        <div class="row">
                            @php $imgCount = 0; @endphp

                            @foreach ($section->content as $k => $item)
                                @if ($k == 'images')
                                    @php $imgCount = collect($item)->count(); @endphp

                                    @foreach ($item as $imgKey => $image)
                                        <div class="col-lg-4 col-md-4 col-sm-6">
                                            <input type="hidden" name="has_image" value="1">
                                            <div class="image-upload">
                                                <div class="thumb">
                                                    <div class="avatar-preview">
                                                        <div class="profilePicPreview" style="background-image: url({{ getImage('assets/images/site/' . $key . '/' . @$content->data_info->$imgKey, @$section->content->images->$imgKey->size) }})">
                                                            <button type="button" class="remove-image">
                                                                <i class="las la-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type="file" class="profilePicUpload" name="image_input[{{ @$imgKey }}]" id="profilePicUpload{{ $loop->index }}" accept=".png, .jpg, .jpeg">

                                                        <label for="profilePicUpload{{ $loop->index }}" class="btn btn-primary upload-btn" title="{{ __(keyToTitle(@$imgKey)) }}"><i class="las la-upload"></i></label>

                                                        <p class="pt-2">
                                                            @lang('Supported files'): <mark>@lang('jpeg'), @lang('jpg'), @lang('png').</mark>

                                                            @if (@$section->content->images->$imgKey->size)
                                                                @lang('Image size') <mark>{{ @$section->content->images->$imgKey->size }}@lang('px').</mark>
                                                            @endif

                                                            @if (@$section->content->images->$imgKey->thumb)
                                                                @lang('Thumb size') <mark>{{ @$section->content->images->$imgKey->thumb }}@lang('px').</mark>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="@if ($imgCount > 1) col-lg-12 col-md-12 @else col-lg-8 col-md-8 @endif mt-3">
                                        @push('divend')
                                    </div>
                                    @endpush
                                @else
                                    <div class="col-12">
                                        @if ($k != 'images')
                                            @if ($item == 'icon')
                                                <div class="row mb-3">
                                                    <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control iconPicker icon" name="{{ $k }}" value="{{ @$content->data_info->$k }}" autocomplete="off" required>
                                                            <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker">@php echo @$content->data_info->$k; @endphp</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($item == 'textarea')
                                                <div class="row mb-3">
                                                    <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control" name="{{ $k }}" rows="3" required>{{ @$content->data_info->$k }}</textarea>
                                                    </div>
                                                </div>
                                            @elseif($item == 'textarea-editor')
                                                <div class="row mb-3">
                                                    <label class="col-sm-3 col-form-label">{{ __(keyToTitle($k)) }}</label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control nicEdit" rows="8" name="{{ $k }}">{{ @$content->data_info->$k }}</textarea>
                                                    </div>
                                                </div>
                                            @elseif($k == 'select')
                                                @php $selectName = $item->name; @endphp

                                                <div class="row mb-3">
                                                    <label class="col-sm-3 col-form-label required">{{ __(keyToTitle(@$selectName)) }}</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-select" name="{{ @$selectName }}" required>
                                                            @foreach ($item->options as $selectItemKey => $selectOption)
                                                                <option value="{{ $selectItemKey }}" @if (@$content->data_info->$selectName == $selectItemKey) selected @endif>
                                                                    {{ $selectOption }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row mb-3">
                                                    <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" name="{{ $k }}" value="{{ @$content->data_info->$k }}" required>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endforeach

                            @stack('divend')
                        </div>

                        <div class="row pt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Submit')</button>
                                <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if (@$section->element)
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
                        <h5 class="mb-0">@lang('Items')</h5>
                        <div class="d-flex flex-wrap justify-content-sm-end gap-2 align-items-center">
                            <div class="d-inline">
                                <div class="input-group justify-content-end">
                                    <input type="text" name="search_table" class="form-control" placeholder="@lang('Search')...">
                                    <button class="btn btn-label-primary input-group-text"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                            @if ($section->element->modal)
                                <button type="button" class="btn btn-label-primary addBtn">
                                    <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
                                </button>
                            @else
                                <a href="{{ route('admin.site.sections.element', $key) }}" type="button" class="btn btn-label-primary">
                                    <span class="tf-icons las la-plus-circle me-1"></span> @lang('Add New')
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl">
                <div class="card">
                    <div class="card-body table-responsive text-nowrap">
                        <table class="table table-hover custom-data-table">
                            <thead>
                                <tr>
                                    @if (@$section->element->images)
                                        <th>@lang('Image')</th>
                                    @endif

                                    @foreach ($section->element as $k => $type)
                                        @if ($k != 'modal')
                                            @if ($type == 'text' || $type == 'icon')
                                                <th>{{ __(keyToTitle($k)) }}</th>
                                            @elseif($k == 'select')
                                                <th>{{ keyToTitle(@$section->element->$k->name) }}</th>
                                            @endif
                                        @endif
                                    @endforeach

                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0">
                                @forelse ($elements as $data)
                                    <tr>
                                        @if (@$section->element->images)
                                            @php $firstKey = collect($section->element->images)->keys()[0]; @endphp

                                            <td>
                                                <div class="avatar me-2">
                                                    <img src="{{ getImage('assets/images/site/' . $key . '/' . @$data->data_info->$firstKey, @$section->element->images->$firstKey->size) }}" alt="image" class="rounded">
                                                </div>
                                            </td>
                                        @endif

                                        @foreach ($section->element as $k => $type)
                                            @if ($k != 'modal')
                                                @if ($type == 'text' || $type == 'icon')
                                                    @if ($type == 'icon')
                                                        <td>@php echo @$data->data_info->$k; @endphp</td>
                                                    @else
                                                        <td>{{ __(@$data->data_info->$k) }}</td>
                                                    @endif
                                                @elseif($k == 'select')
                                                    @php $dataVal = @$section->element->$k->name; @endphp

                                                    <td>{{ @$data->data_info->$dataVal }}</td>
                                                @endif
                                            @endif
                                        @endforeach

                                        <td>
                                            <div>
                                                @if ($section->element->modal)
                                                    @php
                                                        $images = [];

                                                        if (@$section->element->images) {
                                                            foreach ($section->element->images as $imgKey => $imgs) {
                                                                $images[] = getImage('assets/images/site/' . $key . '/' . @$data->data_info->$imgKey, @$section->element->images->$imgKey->size);
                                                            }
                                                        }
                                                    @endphp

                                                    <button class="btn btn-sm btn-label-primary editBtn" data-id="{{ $data->id }}" data-all="{{ json_encode($data->data_info) }}" @if (@$section->element->images) data-images="{{ json_encode($images) }}" @endif>
                                                        <span class="tf-icons las la-pen me-1"></span> @lang('Edit')
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.site.sections.element', [$key, $data->id]) }}" class="btn btn-sm btn-label-primary">
                                                        <span class="tf-icons las la-pen me-1"></span> @lang('Edit')
                                                    </a>
                                                @endif

                                                <button class="btn btn-sm btn-label-danger decisionBtn" data-question="@lang('Are you confirming the removal of this item?')" data-action="{{ route('admin.site.remove', $data->id) }}">
                                                    <span class="tf-icons las la-trash me-1"></span> @lang('Delete')
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
                </div>
            </div>
        </div>

        {{-- Add Modal --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">@lang('Add New Item')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <hr>
                    <form action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">

                        <div class="modal-body">
                            @foreach ($section->element as $k => $type)
                                @if ($k != 'modal')
                                    @if ($type == 'icon')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control iconPicker icon" name="{{ $k }}" autocomplete="off" required>
                                                    <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle(@$section->element->$k->name)) }}</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="{{ @$section->element->$k->name }}" required>
                                                    @foreach ($section->element->$k->options as $selectKey => $options)
                                                        <option value="{{ $selectKey }}">{{ $options }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($k == 'images')
                                        @foreach ($type as $imgKey => $image)
                                            <div class="row mb-3">
                                                <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                                <div class="col-sm-9">
                                                    <input type="hidden" name="has_image" value="1">

                                                    <div class="image-upload">
                                                        <div class="thumb">
                                                            <div class="avatar-preview">
                                                                <div class="profilePicPreview" style="background-image: url({{ getImage('/', @$section->element->images->$imgKey->size) }})">
                                                                    <button type="button" class="remove-image">
                                                                        <i class="las la-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="avatar-edit">
                                                                <input type="file" class="profilePicUpload" name="image_input[{{ @$imgKey }}]" id="addImage{{ $loop->index }}" accept=".png, .jpg, .jpeg" required>

                                                                <label for="addImage{{ $loop->index }}" class="btn btn-primary upload-btn" title="{{ __(keyToTitle(@$imgKey)) }}"><i class="las la-upload"></i></label>

                                                                <p class="pt-2">
                                                                    @lang('Supported files'): <mark>@lang('jpeg'), @lang('jpg'), @lang('png').</mark>

                                                                    @if (@$section->element->images->$imgKey->size)
                                                                        @lang('Image size') <mark>{{ @$section->element->images->$imgKey->size }}@lang('px').</mark>
                                                                    @endif

                                                                    @if (@$section->element->images->$imgKey->thumb)
                                                                        @lang('Thumb size') <mark>{{ @$section->element->images->$imgKey->thumb }}@lang('px').</mark>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif($type == 'textarea')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="{{ $k }}" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    @elseif($type == 'textarea-editor')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control trumEdit" name="{{ $k }}"></textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="{{ $k }}" required>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
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
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">@lang('Update Item')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <hr>
                    <form action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <input type="hidden" name="id">

                        <div class="modal-body">
                            @foreach ($section->element as $k => $type)
                                @if ($k != 'modal')
                                    @if ($type == 'icon')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control iconPicker icon" name="{{ $k }}" autocomplete="off" required>
                                                    <span class="input-group-text input-group-addon existedIcon" data-icon="las la-home" role="iconpicker"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle(@$section->element->$k->name)) }}</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="{{ @$section->element->$k->name }}" required>
                                                    @foreach ($section->element->$k->options as $selectKey => $options)
                                                        <option value="{{ $selectKey }}">{{ $options }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif($k == 'images')
                                        @foreach ($type as $imgKey => $image)
                                            <div class="row mb-3">
                                                <label class="col-sm-3 col-form-label">{{ __(keyToTitle($k)) }}</label>
                                                <div class="col-sm-9">
                                                    <input type="hidden" name="has_image" value="1">

                                                    <div class="image-upload">
                                                        <div class="thumb">
                                                            <div class="avatar-preview">
                                                                <div class="profilePicPreview imageModalUpdate{{ $loop->index }}">
                                                                    <button type="button" class="remove-image"><i class="las la-times"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="avatar-edit">
                                                                <input type="file" class="profilePicUpload" name="image_input[{{ @$imgKey }}]" id="fileUploader{{ $loop->index }}" accept=".png, .jpg, .jpeg">

                                                                <label for="fileUploader{{ $loop->index }}" class="btn btn-primary upload-btn" title="{{ __(keyToTitle(@$imgKey)) }}"><i class="las la-upload"></i></label>

                                                                <p class="pt-2">
                                                                    @lang('Supported files'): <mark>@lang('jpeg'), @lang('jpg'), @lang('png').</mark>

                                                                    @if (@$section->element->images->$imgKey->size)
                                                                        @lang('Image size') <mark>{{ @$section->element->images->$imgKey->size }}@lang('px').</mark>
                                                                    @endif

                                                                    @if (@$section->element->images->$imgKey->thumb)
                                                                        @lang('Thumb size') <mark>{{ @$section->element->images->$imgKey->thumb }}@lang('px').</mark>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif($type == 'textarea')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="{{ $k }}" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    @elseif($type == 'textarea-editor')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control trumEdit" name="{{ $k }}"></textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="{{ $k }}" required>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
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

        <x-decisionModal />
    @endif
@endsection

@push('page-style')
    <style>
        .ck-editor__editable_inline {
            min-height: 100px;
        }

        .iconpicker-popover.fade {
            opacity: 1;
        }
    </style>
@endpush

@push('page-style-lib')
    <link href="{{ asset('assets/admin/css/page/iconpicker.css') }}" rel="stylesheet">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/iconpicker.js') }}"></script>
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
    <script src="{{ asset('assets/admin/js/page/nicEdit.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.addBtn').on('click', function() {
                let modal = $('#addModal');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let modal = $('#editModal');
                let obj = $(this).data('all');
                let images = $(this).data('images');

                if (images) {
                    for (let i = 0; i < images.length; i++) {
                        let imglocation = images[i];

                        $(`.imageModalUpdate${i}`).css("background-image", "url(" + imglocation + ")");
                    }
                }

                $.each(obj, function(index, value) {
                    let element = modal.find('[name=' + index + ']')
                    element.val(value);

                    if (element.hasClass('iconpicker-element')) {
                        let iconElement = $(element).parent().find(".existedIcon");

                        iconElement.html(value)
                    }
                });

                modal.find('[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            if ($(".trumEdit")[0]) {
                ClassicEditor
                    .create(document.querySelector('.trumEdit'))
                    .then(editor => {
                        window.editor = editor;
                    });
            }

            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);

                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                });
            });

            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.input-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });
        })(jQuery);
    </script>
@endpush

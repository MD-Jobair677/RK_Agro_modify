@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <form class="card-body" action="{{ route('admin.site.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="element">

                    @if (@$data)
                        <input type="hidden" name="id" value="{{ $data->id }}">
                    @endif

                    <div class="row justify-content-center">
                        @php $imgCount = 0; @endphp

                        @foreach ($section->element as $k => $content)
                            @if ($k == 'images')
                                @php $imgCount = collect($content)->count(); @endphp

                                @foreach ($content as $imgKey => $image)
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <input type="hidden" name="has_image" value="1">
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview" style="background-image: url({{ getImage('assets/images/site/' . $key . '/' . @$data->data_info->$imgKey, @$section->element->images->$imgKey->size) }})">
                                                        <button type="button" class="remove-image">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type="file" class="profilePicUpload" name="image_input[{{ @$imgKey }}]" id="profilePicUpload{{ $loop->index }}" accept=".png, .jpg, .jpeg" @if (!@$data) required @endif>

                                                    <label for="profilePicUpload{{ $loop->index }}" class="btn btn-primary upload-btn" title="{{ __(keyToTitle(@$imgKey)) }}"><i class="las la-upload"></i></label>

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
                                @endforeach

                                <div class="@if ($imgCount > 1) col-lg-12 col-md-12 @else col-lg-8 col-md-8 @endif mt-3">
                                    @push('divend')
                                </div>
                                    @endpush
                            @else
                                @if ($k != 'images')
                                    @if ($content == 'icon')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control iconPicker icon" name="{{ $k }}" value="{{ @$data->data_info->$k }}" autocomplete="off" required>
                                                    <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker">@php echo @$data->data_info->$k; @endphp</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($content == 'textarea')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="{{ $k }}" rows="3" required>{{ @$data->data_info->$k }}</textarea>
                                            </div>
                                        </div>
                                    @elseif($content == 'textarea-editor')
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">{{ __(keyToTitle($k)) }}</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control nicEdit" rows="8" name="{{ $k }}">{{ @$data->data_info->$k }}</textarea>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        @php $selectName = $content->name; @endphp

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label required">{{ __(keyToTitle(@$selectName)) }}</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="{{ @$selectName }}" required>
                                                    @foreach ($content->options as $selectItemKey => $selectOption)
                                                        <option value="{{ $selectItemKey }}" @if (@$data->data_info->$selectName == $selectItemKey) selected @endif>
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
                                                <input type="text" class="form-control" name="{{ $k }}" value="{{ @$data->data_info->$k }}" required>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endforeach

                        @stack('divend')
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Submit')</button>
                            <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
    <script src="{{ asset('assets/admin/js/page/nicEdit.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

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

@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Email')</th>
                                <th>@lang('Subscribed At')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ showDateTime($subscriber->created_at) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-label-danger decisionBtn" data-question="@lang('Are you confirming the removal of this subscriber?')" data-action="{{ route('admin.subscriber.remove', $subscriber->id) }}">
                                            <span class="tf-icons las la-trash me-1"></span> @lang('Delete')
                                        </button>
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

                @if ($subscribers->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($subscribers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Email Modal --}}
    <div class="modal fade" id="mailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">@lang('Email to Subscribers')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr>
                <form action="{{ route('admin.subscriber.send.email') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Subject')</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label required">@lang('Body')</label>
                            <div class="col-sm-9">
                                <textarea name="body" class="trumEdit"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Send')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Email" />

    <button type="button" class="btn btn-label-primary" data-bs-target="#mailModal" data-bs-toggle="modal">
        <span class="tf-icons las la-envelope me-1"></span> @lang('Send Mail')
    </button>
@endpush

@push('page-style')
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/ckEditor.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            if ($(".trumEdit")[0]) {
                ClassicEditor
                    .create(document.querySelector('.trumEdit'))
                    .then(editor => {
                        window.editor = editor;
                    });
            }
        })(jQuery);
    </script>
@endpush

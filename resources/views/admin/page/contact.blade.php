@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Email')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Subject')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Contacted At')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>
                                        @if($contact->status)
                                            <span class="badge bg-label-success">@lang('Answered')</span>
                                        @else
                                            <span class="badge bg-label-warning">@lang('Unanswered')</span>
                                        @endif
                                    </td>
                                    <td>{{ showDateTime($contact->created_at) }}</td>
                                    <td>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                                data-bs-toggle      = "offcanvas"
                                                data-bs-target      = "#offcanvasBoth"
                                                aria-controls       = "offcanvasBoth"
                                                data-message        = "{{ $contact->message }}"
                                            >
                                                <span class="tf-icons las la-info-circle me-1"></span> @lang('Details')
                                            </button>

                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-label-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">@lang('Action')</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        @if($contact->status)
                                                            <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming to mark this contact as unanswered?')" data-action="{{ route('admin.contact.status', $contact->id) }}">
                                                                <span class="las la-ban fs-6 link-warning"></span>
                                                                @lang('Unanswered')
                                                            </button>
                                                        @else
                                                            <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming to mark this contact as answered?')" data-action="{{ route('admin.contact.status', $contact->id) }}">
                                                                <span class="las la-check-circle fs-6 link-primary"></span>
                                                                @lang('Answered')
                                                            </button>
                                                        @endif
                                                    </li>

                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn" data-question="@lang('Are you confirming the removal of this contact?')" data-action="{{ route('admin.contact.remove', $contact->id) }}">
                                                            <span class="las la-trash fs-6 link-danger"></span> @lang('Delete')
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($contacts->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($contacts) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth" aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h4 id="offcanvasBothLabel" class="offcanvas-title">@lang('Contact Details')</h4>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="mb-4">
                <h5>@lang('User Message')</h5>
                <div class="border rounded p-3">
                    <p class="userMessage mb-0"></p>
                </div>
            </div>
            <button type="button" class="btn btn-secondary d-grid w-100 mt-4" data-bs-dismiss="offcanvas">
                @lang('Close')
            </button>
        </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Email" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.detailBtn').on('click', function() {
                let message = $(this).data('message');
                $('.userMessage').text(message);
            });
        })(jQuery);
    </script>
@endpush

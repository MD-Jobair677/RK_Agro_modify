@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.role.create') }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Name')</th>
                                <th class="text-center">@lang('Description')</th>
                                <th class="text-center">@lang('Created At')</th>
                                <th class="text-center">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $role->name }}</td>
                                    <td class="text-center">{{ strLimit($role->description,30) }}</td>
                                    <td class="text-center">{{ showDateTime($role->created_at,'d-m-y, h:i A') }}</td>
                                    <td class="text-center">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                                data-bs-toggle      = "offcanvas" data-bs-target      = "#offcanvasBoth"
                                                aria-controls       = "offcanvasBoth"
                                                data-message        = "{{ $role->description }}">
                                                <span class="tf-icons las la-info-circle me-1"></span> @lang('Details')
                                            </button>

                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-label-primary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">@lang('Action')</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a 
                                                            class="dropdown-item " href="{{ route('admin.role.edit', $role->id) }}">
                                                            <span class="las la-pen fs-6 link-warning"></span>
                                                            @lang('Edit')
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <button type="button" class="dropdown-item decisionBtn"
                                                            data-question="@lang('Are you confirming the removal of this role? if you delete this role, all roles for this administrator will be deleted.')"
                                                            data-action="{{ route('admin.role.remove', $role->id) }}">
                                                            <span class="las la-trash fs-6 link-danger"></span>
                                                            @lang('Delete')
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
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

                @if ($roles->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($roles) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth"
        aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h4 id="offcanvasBothLabel" class="offcanvas-title">@lang('Role Details')</h4>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="mb-4">
                <h5>@lang('Role Description')</h5>
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
    <x-searchForm placeholder="Search role name..." dateSearch="no" />
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

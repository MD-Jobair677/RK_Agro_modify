@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    @can('has-permission', 'admin create')
                        <a href="{{ route('admin.admin.create') }}" class="btn btn-sm btn-success">
                            <span class="tf-icons las la-plus-circle me-1"></span>
                            @lang('Add New')
                        </a>
                    @endcan
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Image')</th>
                                <th class="text-center">@lang('Name')</th>
                                <th class="text-center">@lang('Email')</th>
                                <th class="text-center">@lang('User Name')</th>
                                <th class="text-center">@lang('Contact')</th>
                                @can('has-permission', 'admin delete')
                                    <th class="text-center">@lang('Actions')</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($admins as $admin)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><img class="rounded"
                                            src="{{ getImage(getFilePath('adminProfile') . '/' . $admin->image) }}"
                                            alt="admin-image" style="width:50px"></td>

                                    <td class="text-center">{{ $admin->name }}</td>
                                    <td class="text-center">{{ $admin->email }}</td>
                                    <td class="text-center">{{ $admin->username }}</td>
                                    <td class="text-center">{{ $admin->contact }}</td>
                                    @can('has-permission', 'admin delete')
                                        <td class="text-center">
                                            <div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-danger decisionBtn"
                                                        data-question="@lang('Are you confirming the removal of this admin?')"
                                                        data-action="{{ route('admin.admin.remove', $admin->id) }}">
                                                        <span class="las la-trash fs-6 link-white"></span>
                                                        @lang('Delete')
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($admins->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($admins) }}
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
    <x-searchForm placeholder="Search admin name..." dateSearch="no" />
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

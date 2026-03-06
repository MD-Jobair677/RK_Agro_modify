@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.cattle.create') }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Image')</th>
                                <th class="text-center">@lang('Tag Number')</th>
                                <th class="text-center">@lang('Cattle Name')</th>
                                <th class="text-center">@lang('Category Name')</th>
                                <th class="text-center">@lang('Asking Price')</th>
                                <th class="text-center">@lang('Weight')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th class="text-center">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($cattles as $cattle)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img class="rounded"
                                            src="{{ getImage(getFilePath('cattle') . '/' . optional($cattle->primaryImage)->image_path) }}"
                                            alt="admin-image" style="width:50px">
                                    </td>

                                    <td class="text-center">{{ $cattle->tag_number }}</td>
                                    <td class="text-center">{{ $cattle->name }}</td>
                                    <td class="text-center">{{ $cattle->cattleCategory->name }}</td>
                                    <td class="text-center">{{ $cattle->asking_price }}</td>
                                    <td class="text-center">{{ $cattle->purchase_weight }}</td>
                                    <td class="text-center">@php echo $cattle->statusBadge @endphp</td>
                                    @if($cattle->status == 1 || $cattle->status == 2)
                                    <td class="text-center">
                                        <div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-label-primary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">@lang('Action')</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.cattle.edit', $cattle->id) }}">
                                                            <span class="las la-pen fs-6 link-warning"></span>
                                                            @lang('Edit Cattle')
                                                        </a>
                                                    </li>
                                                    @if($cattle->status == 1)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.cattle.edit_weight', $cattle->id) }}">
                                                            <span class="las la-pen fs-6 link-warning"></span>
                                                            @lang('Edit Weight & Purchase')
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.cattle.detail', $cattle->id) }}">
                                                            <span class="tf-icons las la-info-circle me-1"></span>
                                                            @lang('Details')
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($cattles->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($cattles) }}
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
    <x-searchForm placeholder="Search Cattle by Tag Number..." dateSearch="no" />
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

@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.inventory.issue.create') }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Item')</th>
                                <th class="text-center">@lang('Warehous')</th>
                                <th class="text-center">@lang('Issue Date')</th>
                                <th class="text-center">@lang('Issue Quantity')</th>
                                <th class="text-center">@lang('Unite Of Measurement')</th>
                                <th class="text-center">@lang('Actions')</th>
                          
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($invIssues as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->item->name }}</td>
                                    <td class="text-center">{{ $item->warehouse->name }}</td>
                                    <td class="text-center">{{ $item->issue_date }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth"
                                                data-inv_stock="{{ $item }}" aria-controls="offcanvasBoth">
                                                <span class="tf-icons las la-info-circle me-1"></span>
                                                @lang('Details')
                                            </button>
                                            {{-- <a href="{{ route('admin.common.item.edit', $item->id) }}"
                                                class="btn btn-sm btn-warning">

                                                <span class="tf-icons las la-pen me-1"></span>
                                                @lang('Edit')
                                            </a> --}}
                                        </div>
                                    </td>

                                     {{-- <td class="text-center">{{ $item->quantity }}</td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($invIssues->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($invIssues) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth"
        aria-labelledby="offcanvasBothLabel">

        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <div class="mb-4">

                <div class="basicData"></div>
            </div>
            <button type="button" class="btn btn-secondary d-grid w-100 mt-4" data-bs-dismiss="offcanvas">
                @lang('Close')
            </button>
        </div>
    </div>



    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Customer name..." dateSearch="no" />
@endpush

@push('page-script')
    <script>
        $('.detailBtn').on('click', function() {
            let invStockData = $(this).data('inv_stock');
            console.log(invStockData);

            let basicHtml = `<div class="mb-4">
                                <h5>@lang('Customer Information')</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Item')</b>
                                        <span>${invStockData.item.name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Warehouse')</b>
                                        <span>${invStockData.warehouse.name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Supplier')</b>
                                        <span>${invStockData.supplier.name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Purchase Date')</b>
                                        <span>${invStockData.purchase_date}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Quantity In')</b>
                                        <span>${invStockData.quntity_in}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Unit Of Measurement')</b>
                                        <span>${invStockData.unit_of_measurement}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Rate Per Unit')</b>
                                        <span>${invStockData.rate_per_unit}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Total Amount')</b>
                                        <span>${invStockData.total_amount}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Remark')</b>
                                        <span>${invStockData.remark}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Reference')</b>
                                        <span>${invStockData.reference}</span>
                                    </li>`;
            basicHtml += `</ul>
                    </div>`;

            $('.basicData').html(basicHtml);

        });
    </script>
@endpush

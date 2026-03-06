@extends('admin.layouts.master')


<style>
    .row-danger td {
        background-color: #dc3545 !important;
        /* bootstrap bg-danger */
        color: #fff !important;
        font-weight: bold;
    }

    /* Action TD will stay normal */
    .row-danger td.action-td {
        background-color: #fff !important;
        color: #000 !important;
        font-weight: normal;
    }
</style>
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.inventory.stock.create', $warehouse->name) }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <a href="{{ route('admin.inventory.wh.stock.history', $warehouse->name) }}"
                        class="btn btn-sm btn-warning">
                        <span class="fa-solid fa-ellipsis-vertical me-1"></span>
                        @lang('Stock History')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Item')</th>
                                {{-- <th class="text-center">@lang('Warehous')</th> --}}
                                {{-- <th class="text-center">@lang('Supplier')</th> --}}
                                <th class="text-center">@lang('Quantity')</th>
                                <th class="text-center">@lang('Last Purchase Date')</th>
                                <th class="text-center">@lang('Last Stock In')</th>
                                <th class="text-center">@lang('Last Issue Date')</th>
                                <th class="text-center">@lang('Last Stock Out')</th>
                                <th class="text-center">@lang('Unite Of Measurement')</th>
                                {{-- <th class="text-center">@lang('Rate Per Unit')</th>
                                <th class="text-center">@lang('Total Amount')</th> --}}
                                <th class="text-center">@lang('Actions')</th>
                                <th class="text-center">@lang('Update')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($invStocks as $item)
                                <tr class="{{ $item->quantity <= $item->item->record_level ? 'row-danger' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->item->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ $item->purchase_date }}</td>
                                    <td class="text-center">{{ $item->quantity_in }}</td>
                                    <td class="text-center">{{ $item->last_issue_date }}</td>
                                    <td class="text-center">{{ $item->quantity_out }}</td>
                                    <td class="text-center">{{ $item->unit_of_measurement }}</td>

                                    {{-- Action column (NOT RED) --}}
                                    <td class="text-center action-td">
                                        <div>
                                            <a href="{{ route('admin.inventory.stock.edit', [$warehouse->name, $item->item->id]) }}"
                                                class="btn btn-sm btn-success">
                                                <span>+</span>
                                                @lang('Increase')
                                            </a>

                                            <a href="{{ route('admin.inventory.issue.create', [$warehouse->name, $item->item->id]) }}"
                                                class="btn btn-sm btn-danger">
                                                @lang('-Decrease')
                                            </a>
                                        </div>
                                    </td>

                                    <td><a href="{{ route('admin.inventory.inventory.stock.history', [$warehouse->name, $item->item->id]) }}" class="btn btn-sm btn-primary">@lang('Update')</a></td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($invStocks->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($invStocks) }}
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
    <x-searchForm placeholder="Search Item name..." dateSearch="no" />
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
                                        <span>${invStockData.quantity_in}</span>
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

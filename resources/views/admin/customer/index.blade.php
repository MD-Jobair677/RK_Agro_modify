@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <a href="{{ route('admin.customer.create') }}" class="btn btn-sm btn-success">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">@lang('SI')</th>
                                <th class="text-center">@lang('Image')</th>
                                <th class="text-center">@lang('Full Name')</th>
                                <th class="text-center">@lang('Phone')</th>
                                <th class="text-center">@lang('Email')</th>
                                <th class="text-center">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($customers as $item)
                            {{-- @dd($item) --}}
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img class="rounded"
                                            src="{{ getImage(getFilePath('customer') . '/' . $item->image_path) }}"
                                            alt="admin-image" style="width:50px">
                                    </td>
                                    <td class="text-center">{{ $item->fullname }}</td>
                                    <td class="text-center">{{ $item->phone }}</td>
                                    <td class="text-center">{{ $item->email }}</td>
                                    <td class="text-center">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth"
                                                data-customer="{{ $item }}" 
                                                data-customer_image_path="{{ getImage(getFilePath('customer') . '/' . $item->image_path) }}"
                                                aria-controls="offcanvasBoth">
                                                <span class="tf-icons las la-info-circle me-1"></span>
                                                @lang('Details')
                                            </button>
                                            <a href="{{ route('admin.customer.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                               
                                                <span class="tf-icons las la-pen me-1"></span>
                                                @lang('Edit')
                                            </a>
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

                @if ($customers->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($customers) }}
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
            let customerData = $(this).data('customer');
            let customerImagePath = $(this).data('customer_image_path');
            console.log(customerImagePath);
            
            let basicHtml = `<div class="mb-4">
                                <h5>@lang('Customer Information')</h5>
                                <div class="text-center mb-3">
                                    <img class="rounded" src="${customerImagePath}" alt="customer-image" style="width:200px">
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('First Name')</b>
                                        <span>${customerData.first_name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Last Name')</b>
                                        <span>${customerData.last_name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Full Name')</b>
                                        <span>${customerData.full_name}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Email')</b>
                                        <span>${customerData.email}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('Phone')</b>
                                        <span>${customerData.phone}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <b>@lang('National ID Number')</b>
                                        <span>${customerData.nid_number}</span>
                                    </li>
                                    `;
                                basicHtml += `</ul>
                                        </div>`;

            $('.basicData').html(basicHtml);

        });
    </script>
@endpush

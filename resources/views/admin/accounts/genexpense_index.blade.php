@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    @if ($expTyp == 3)
                        <a href="{{ route('admin.account.gen_expns.create') }}" class="btn btn-sm btn-success">
                            <span class="tf-icons las la-plus-circle me-1"></span>
                            @lang('Add New')
                        </a>
                    @endif
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('SI')</th>
                                {{-- <th class="text-center">@lang('Account Head')</th>
                                <th class="text-center">@lang('Account Sub Head')</th> --}}
                                <th class="text-center">@lang('Expense Type')</th>
                                <th class="text-center">@lang('Cost Amount')</th>
                                <th class="text-center">@lang('Date Of Expense')</th>
                                <th class="text-center">@lang('Purpose')</th>
                                <th class="text-center">@lang('Note')</th>
                                <th class="text-center">@lang('Created At')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($genExpenses as $expense)
                                @php
                                    $label = match ($expense->expense_type) {
                                        1 => 'Food Expense',
                                        2 => 'Cattle Expense',
                                        3 => 'General Expense',
                                        4 => 'Medicine Expense',
                                        default => 'Unknown',
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    {{-- <td class="text-center">{{ $expense->accountHead->name }}</td>
                                    <td class="text-center">{{ $expense->accountSubHead->name }}</td> --}}
                                    <td class="text-center">{{ $label }} </td>
                                    <td class="text-center">{{ $expense->cost_amount }}</td>
                                    <td class="text-center">{{ showDateTime($expense->expense_date, 'd-m-y, h:i A') }}</td>
                                    <td class="text-center">{{ $expense->purpose }}</td>
                                    <td class="text-center">{{ $expense->note }}</td>
                                    <td class="text-center">{{ showDateTime($expense->created_at, 'd-m-y, h:i A') }}</td>
                                    <td class="text-center">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-label-info detailBtn"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth"
                                                data-customer="{{ $expense }}" aria-controls="offcanvasBoth">
                                                <span class="tf-icons las la-info-circle me-1"></span>
                                                @lang('Details')
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

                @if ($genExpenses->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($genExpenses) }}
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
    <x-searchForm placeholder="Search Expense..." dateSearch="no" />
@endpush

@push('page-script')
    <script>
        document.querySelectorAll('.detailBtn').forEach(button => {
            button.addEventListener('click', function(event) {
                // find inside the offcanvas class and id
                const form = document.getElementById('offcanvasForm');
                const codeInput = document.getElementsByName('head_code')[0];
                const nameInput = document.getElementsByName('name')[0];
                const descInput = document.getElementsByName('description')[0];
                const statusInput = document.getElementsByName('status')[0];
                const formHeading = document.getElementsByClassName('offcanvas-title')[0];
                const formButton = form.querySelector('button[type="submit"]');
                form.reset();

                // Example: Fetch and display data from the button attributes
                let url = this.getAttribute('data-url');
                let formTextInformations = JSON.parse(this.getAttribute('data-form-text-informations'));
                let accHead = JSON.parse(this.getAttribute('data-accHead'));

                // Optionally, set this information inside the offcanvas
                form.setAttribute('action', url);
                formHeading.textContent = formTextInformations.heading;
                formButton.textContent = formTextInformations.button_name;

                if (accHead) {
                    codeInput.value = accHead.acc_head_code;
                    nameInput.value = accHead.name;
                    descInput.value = accHead.description;
                    statusInput.value = accHead.status;
                }
            });
        });
    </script>
@endpush

@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <button type="button" class="btn btn-sm btn-success detailBtn" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasBoth"
                        data-url="{{ route('admin.account.sub_head.store') }}" 
                        data-form-text-informations ='{"heading":"Create Account Head","button_name":"Create"}'
                        aria-controls="offcanvasBoth">
                        <span class="tf-icons las la-plus-circle me-1"></span>
                        @lang('Add New')
                    </button>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('SI')</th>
                                <th class="text-center">@lang('Main Head')</th>
                                <th class="text-center">@lang('Code')</th>
                                <th class="text-center">@lang('Name')</th>
                                <th class="text-center">@lang('Description')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th class="text-center">@lang('Created At')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($accountSubHeads as $subHead)
                            {{-- @dd($subHead) --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $subHead->head->name }}</td>
                                    <td class="text-center">{{ $subHead->acc_sub_head_code }}</td>
                                    <td class="text-center">{{ $subHead->name }}</td>
                                    <td class="text-center">{{ $subHead->name }}</td>
                                    <td class="text-center">@php echo $subHead->statusBadge @endphp</td>
                                    <td class="text-center">{{ showDateTime($subHead->created_at,'d-m-y, h:i A') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning btn-label-info detailBtn"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth"
                                            data-url="{{ route('admin.account.sub_head.update', $subHead->id) }}"
                                             data-form-text-informations ='{"heading":"Update Account Head","button_name":"Update"}'
                                            data-accHead="{{ $subHead }}" aria-controls="offcanvasBoth">
                                            <span class="tf-icons las la-pen me-1"></span> @lang('Edit')
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

                @if ($accountSubHeads->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($accountSubHeads) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth"
        aria-labelledby="offcanvasBothLabel">
        <div class="offcanvas-header">
            <h4 id="offcanvasBothLabel" class="offcanvas-title">@lang('Create Sub Account Head')</h4>
        </div>
        <div class="offcanvas-body my-auto mx-0">
            <div class="mb-4 border rounded p-3">
               
                <form id="offcanvasForm" class="card-body" action="#" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Account Head')</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="acc_head_id" required>
                                        @forEach ($accountHeads as $accHead)
                                            <option value="{{$accHead->id}}">{{ $accHead->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Code')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="head_code"
                                        placeholder="@lang('Head Code')" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Name')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="@lang('Head Name')" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Description')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="description"
                                        placeholder="@lang('Description')" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Status')</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="status" required>
                                        <option value="1">@lang('Active')</option>
                                        <option value="0">@lang('Inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 sds">@lang('Create')</button>
                            <button type="reset" class="btn btn-label-secondary"
                                data-bs-dismiss="offcanvas">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Search Category..." dateSearch="no" />
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
                    codeInput.value = accHead.acc_sub_head_code;
                    nameInput.value = accHead.name;
                    descInput.value = accHead.description;
                    statusInput.value = accHead.status;
                }
            });
        });
    </script>
@endpush

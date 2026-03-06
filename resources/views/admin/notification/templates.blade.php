@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Subject')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($templates as $template)
                                <tr>
                                    <td>{{ __($template->name) }}</td>
                                    <td>{{ __($template->subj) }}</td>
                                    <td>
                                        <a href="{{ route('admin.notification.template.edit', $template->id) }}" class="btn btn-sm btn-label-primary">
                                            <span class="tf-icons las la-edit me-1"></span>
                                        </a>
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
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <div class="input-group justify-content-end">
        <input type="text" name="search_table" class="form-control" placeholder="@lang('Search')...">
        <button class="btn btn-label-primary input-group-text"><i class="fa fa-search"></i></button>
    </div>
@endpush

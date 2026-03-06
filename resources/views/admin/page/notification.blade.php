@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Created At')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>

                        <tbody class="table-border-bottom-0">
                            @forelse ($notifications as $notification)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.system.notification.read', $notification->id) }}">
                                            {{ __($notification->title) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($notification->is_read)
                                            <span class="badge bg-label-success">@lang('Read')</span>
                                        @else
                                            <span class="badge bg-label-warning">@lang('Unread')</span>
                                        @endif
                                    </td>
                                    <td>{{ showDateTime($notification->created_at) }}</td>
                                    <td>
                                        @if ($notification->click_url != '#')
                                            <a href="{{ route('admin.system.notification.read', $notification->id) }}" class="btn btn-sm btn-label-primary">
                                                <span class="tf-icons las la-link me-1"></span> @lang('Check')
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-label-danger decisionBtn" data-question="@lang('Are you confirming the removal of this notification?')" data-action="{{ route('admin.system.notification.remove', $notification->id) }}">
                                            <span class="tf-icons las la-trash me-1"></span> @lang('Delete')
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

                @if ($notifications->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($notifications) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-decisionModal />
@endsection

@if (count($notifications))
    @push('breadcrumb')
        <a href="{{ route('admin.system.notification.read.all') }}" class="btn btn-label-primary">
            <span class="tf-icons las la-check-double me-1"></span> @lang('Mark all as read')
        </a>

        <button type="submit" class="btn btn-label-danger decisionBtn" data-question="@lang('Are you confirming the removal all notifications?')" data-action="{{ route('admin.system.notification.remove.all') }}">
            <span class="tf-icons las la-trash me-1"></span> @lang('Remove All')
        </button>
    @endpush
@endif

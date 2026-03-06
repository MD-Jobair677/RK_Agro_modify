@extends('admin.layouts.master')
@section('master')
    <div class="row">
        <div class="col-xxl">
            <div class="card">
                <div class="card-body table-responsive text-nowrap fixed-min-height-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Joining')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="ml-5">
                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="@lang('user')" class="rounded-circle" width="35">
                                            </div>
                                            <span class="fw-bold">{{$user->fullname}}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.user.details', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $setting->cur_sym }}{{ showAmount($user->balance) }}</td>
                                    <td>{{ showDateTime($user->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.login', $user->id) }}" class="btn btn-sm btn-label-primary" title="@lang('Login User')"><i class="fa-solid fa-arrow-right-to-bracket"></i></a>
                                        <a href="{{ route('admin.user.details', $user->id) }}" class="btn btn-sm btn-label-primary" title="@lang('Details')"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer pagination justify-content-center">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="Username/Email"/>
@endpush


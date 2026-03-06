@extends('admin.layouts.master')

@section('master')
    <form action="{{ route('admin.role.set.update', $admin->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <h5 class="card-header">@lang('Admin Role Update')</h5>
                    <hr class="mt-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label required">@lang('Admin Name')</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $admin->name }}" readonly placeholder="@lang('Enter your role name')" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <h5 class="card-header">@lang('Roles Preferences')</h5>
                    <hr class="mt-0">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($roles ?? [] as $role)
                                <div class="col-md-6">
                                    <div class="d-flex m-3">
                                        <div class="flex-grow-1 row">
                                            <div class="col-9 mb-sm-0 mb-2">
                                                <h6 class="mb-0">{{ $role->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $role->description }}
                                                </small>
                                            </div>
                                            <div class="col-3 text-end">
                                                <label class="switch me-0">
                                                    <input type="checkbox" class="switch-input" value="{{ $role->id }}"
                                                        {{ $admin->hasRole($role->name) ? 'checked' : '' }} name="roles[]">
                                                    @include('admin.partials.switcher')
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Save')</button>
                <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
            </div>
        </div>
    </form>
@endsection

@push('page-style')
    <style>
        label.required:after {
            content: '';
        }
    </style>
@endpush

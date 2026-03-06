@extends('admin.layouts.master')

@section('master')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">@lang('Role Edit')</h5>
                <hr class="mt-0">
                <form class="card-body" action="{{route('admin.role.update',$role->id)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Role Name')</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="{{$role->name}}" placeholder="@lang('Enter your role name')" required>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label required">@lang('Role Description')</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" rows="5" cols="5" name="description" placeholder="@lang('Enter your role description')" required>{{$role->description}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">@lang('Update')</button>
                            <button type="reset" class="btn btn-label-secondary">@lang('Cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/select2.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/select2.js') }}"></script>
@endpush


@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.select2').select2({
                placeholder: "@lang('Select value')",
                dropdownParent: '.select2-design'
            });
        })(jQuery);
    </script>
@endpush

@extends('admin.layouts.master')

@section('master')
    <div class="row">
        @foreach($themes as $theme)
            <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card theme-card h-100">
                    <img class="card-img-top" src="{{ $theme['image'] }}" alt="Card image cap" />
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ __(keyToTitle($theme['name'])) }}</h5>

                        @if ($setting->active_theme == $theme['name'])
                            <button type="button" class="btn btn-success" disabled>
                                <i class="lar la-check-circle me-1"></i> @lang('Activated')
                            </button>
                        @else
                            <button type="button" class="btn btn-label-primary activeBtn" data-name="{{ $theme['name'] }}">
                                <i class="las la-question-circle me-1"></i> @lang('Make Active')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Active Modal --}}
    <div class="modal-onboarding modal fade animate__animated" id="activeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="onboarding-media">
                        <div class="mx-2">
                            <img src="{{ asset('assets/admin/images/light.png') }}" alt="light" width="100" class="img-fluid">
                        </div>
                    </div>
                    <div class="onboarding-content mb-0">
                        <h4 class="onboarding-title text-body">@lang('Make Your Decision')</h4>
                        <div class="onboarding-info">@lang('Are you confirming the activation of this theme for frontend?')</div>
                    </div>
                </div>
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="name">

                    <div class="modal-footer border-0 justify-content-center">
                        <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('No')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
    <style>
        @media only screen and (max-width: 575px) {
            .theme-card {
                max-width: 320px;
                margin: auto;
            }
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.activeBtn').on('click', function() {
                let modal = $('#activeModal');

                modal.find('[name=name]').val($(this).data('name'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

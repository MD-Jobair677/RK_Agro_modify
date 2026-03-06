{{-- Decision Modal --}}
<div class="modal-onboarding modal fade animate__animated" id="decisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">

                <div class="onboarding-content mb-0">
                    <h4 class="onboarding-title text-body">@lang('Make Your Decision')</h4>
                    <div class="onboarding-info question"></div>
                </div>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">@lang('No')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $(document).on('click', '.decisionBtn', function() {
                let modal = $('#decisionModal');
                let data = $(this).data();

                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

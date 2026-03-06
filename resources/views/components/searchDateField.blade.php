<div class="input-group justify-content-end">
    <input type="search" name="date" class="form-control datepicker-here" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-format="Y-m-d" data-position='bottom right' placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}" value="{{ request()->search }}">
    <button class="btn btn-label-primary input-group-text" type="submit">
        <i class="fa fa-search"></i>
    </button>
</div>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.datepicker-here').on('input keyup keydown keypress', function() {
                return false;
            });

            if (!$('.datepicker-here').val()) {
                $('.datepicker-here').datepicker();
            }
        })(jQuery);
    </script>
@endpush

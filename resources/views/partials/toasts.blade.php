<link rel="stylesheet" href="{{ asset('assets/universal/css/iziToast.min.css') }}">
<script src="{{ asset('assets/universal/js/iziToast.min.js') }}"></script>

@if (session()->has('toasts'))
    @foreach (session('toasts') as $msg)
        <script>
            "use strict";

            iziToast.{{ $msg[0] }}({
                message: "{{ __($msg[1]) }}",
                position: "bottomCenter"
            });
        </script>
    @endforeach
@endif

@if(isset($errors) && $errors->any())
    @php
        $collection = collect($errors->all());
        $errors = $collection->unique();
    @endphp

    <script>
        "use strict";

        @foreach ($errors as $error)
            iziToast.error({
                message: '{{ __($error) }}',
                position: "bottomCenter"
            });
        @endforeach
    </script>
@endif

<script>
    "use strict";

    function showToasts(status, message) {
        if (typeof message == 'string') {
            iziToast[status]({
                message: message,
                position: "bottomCenter"
            });
        } else {
            $.each(message, function(i, val) {
                iziToast[status]({
                    message: val,
                    position: "bottomCenter"
                });
            });
        }
    }
</script>

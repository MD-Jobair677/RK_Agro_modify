@props([
    'placeholder' => 'Search...',
    'btn'         => 'btn--primary',
    'dateSearch'  => 'no',
    'keySearch'   => 'yes',
])

<form action="" method="GET" class="d-flex flex-wrap gap-2">
    @if ($keySearch == 'yes')
        <div class="d-inline">
            <x-searchKeyField placeholder="{{ $placeholder }}" />
        </div>
    @endif

    @if ($dateSearch == 'yes')
        <div class="d-inline">
            <x-searchDateField />
        </div>
    @endif
</form>

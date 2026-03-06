@props(['placeholder' => 'Search...'])

<div class="input-group justify-content-end">
    <input type="search" name="search" class="form-control" placeholder="{{ __($placeholder) }}" value="{{ request()->search }}">
    <button class="btn btn-label-primary input-group-text" type="submit">
        <i class="fa fa-search"></i>
    </button>
</div>

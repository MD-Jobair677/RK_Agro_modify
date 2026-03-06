@extends($activeTheme . 'layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Withdraw Via') {{ $withdraw->method->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <p class="fw-bold withdraw-preview-text">
                                @lang('You have requested a withdrawal of') <span class="text--base">{{ showAmount(@$withdraw->amount) . ' ' . __($setting->site_cur) }}</span>, @lang('You will get') <span class="text--base">{{ showAmount(@$withdraw->final_amount) . ' ' . @$withdraw->currency }}</span>.
                            </p>
                            @php echo @$withdraw->method->guideline @endphp
                        </div>
                        <x-phinix-form identifier="id" identifierValue="{{ @$withdraw->method->form_id }}" />
                        @if(auth()->user()->ts)
                        <div class="form-group">
                            <label>@lang('Google Authenticator Code')</label>
                            <input type="text" name="authenticator_code" class="form-control form--control" required>
                        </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


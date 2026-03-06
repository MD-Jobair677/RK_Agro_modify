@extends($activeTheme . 'layouts.master')
@section('content')
    <div class="dashboard py-60">
        <div class="container">
            @if (@$user->kc == ManageStatus::UNVERIFIED || @$user->kc == ManageStatus::PENDING)
                <div class="row justify-content-center" data-aos="fade-up" data-aos-duration="1500">
                    <div class="col-12">
                        <div class="section-heading">
                            @if (@$user->kc == ManageStatus::UNVERIFIED)
                                <div class="alert alert-danger" role="alert">
                                    <h6 class="alert-heading mb-2">{{ __(@$kycContent->data_info->verification_required_heading) }}</h4>
                                    <p>{{ __(@$kycContent->data_info->verification_required_details) }} <a href="{{ route('user.kyc.form') }}">@lang('Click here')</a> @lang('to verify.')</p>
                                </div>
                            @elseif (@$user->kc == ManageStatus::PENDING)
                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading mb-2">{{ __(@$kycContent->data_info->verification_pending_heading) }}</h4>
                                    <p>{{ __(@$kycContent->data_info->verification_pending_details) }} <a href="{{ route('user.kyc.data') }}">@lang('See')</a> @lang('kyc data.')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">{{ __($pageTitle) }}</h5>
                </div>
                <div class="card-body">
                    <p>
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rerum sunt ducimus laboriosam commodi nesciunt accusamus? Sunt in, minus ex, a eveniet inventore facilis doloribus placeat corrupti repudiandae sint esse nesciunt.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatum unde, vitae esse eum perspiciatis consectetur nisi, atque repellendus, cumque magnam ab a inventore quis nobis quod quisquam omnis. In, possimus!
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection


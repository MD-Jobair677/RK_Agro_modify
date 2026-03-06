@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-5">
                <div class="text-end">
                    <a href="{{ route('home') }}" class="fw-bold home-link"> <i class="las la-long-arrow-alt-left"></i>
                        @lang('Go to Home')</a>
                </div>
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="verification-code-wrapper">
                                <div class="verification-area">
                                    <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                                    <form action="" method="POST" class="verification-code-form">
                                        @csrf
                                        <p class="verification-text">@lang('A 6 digit verification code sent to your email address')
                                            : {{ showEmailAddress($email) }}</p>
                                        <input type="hidden" name="email" value="{{ $email }}">

                                        @include('partials.verificationCode')

                                        <div class="form-group">
                                            <button type="submit" class="btn btn--base w-100">@lang('Save')</button>
                                        </div>

                                        <div class="form-group">
                                            @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                            <a href="{{ route('user.password.request.form') }}">@lang('Try to send again')</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

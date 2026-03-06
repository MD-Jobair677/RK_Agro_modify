<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {
    //User Login and Logout 
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'loginForm')->name('login.form');
        Route::post('/login', 'login')->name('login');
        Route::get('logout', 'logout')->middleware('auth')->name('logout');
    });

    // User Registration
    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'registerForm')->name('register');
        Route::post('register', 'register')->middleware('register.status');
        Route::post('check-user', 'checkUser')->name('check.user');
    });

    // Forgot Password
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Reset Password
    Route::controller('ResetPasswordController')->prefix('password/reset')->name('password.')->group(function () {
        Route::get('form/{token}', 'resetForm')->name('reset.form');
        Route::post('/', 'resetPassword')->name('reset');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    // Authorization
    Route::controller('User\AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware('authorize.status')->namespace('User')->group(function () {
    
        //User
        Route::controller('UserController')->group(function () {
            //Dashboard
            Route::get('dashboard', 'home')->name('home');

            //KYC
            Route::prefix('kyc')->name('kyc.')->group(function () {
                Route::get('data', 'kycData')->name('data');
                Route::get('form', 'kycForm')->name('form');
                Route::post('form', 'kycSubmit');
            });

            //Profile
            Route::get('profile', 'profile')->name('profile');
            Route::post('profile', 'profileUpdate');

            //Password
            Route::get('change/password', 'password')->name('change.password');
            Route::post('change/password', 'passwordChange');

            //2FA
            Route::prefix('twofactor')->name('twofactor.')->group(function () {
                Route::get('/', 'show2faForm')->name('form');
                Route::post('enable', 'enable2fa')->name('enable');
                Route::post('disable', 'disable2fa')->name('disable');
            });

            //TRX & Dposit
            Route::any('deposit/history', 'depositHistory')->name('deposit.history');
            Route::get('transactions', 'transactions')->name('transactions');

            //File Download
            Route::get('file-download', 'fileDownload')->name('file.download');
        });

        
        //Withdraw
        Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function(){
            Route::get('/', 'withdrawMoney');
            Route::post('/', 'withdrawStore')->name('.money');
            Route::get('preview', 'withdrawPreview')->name('.preview');
            Route::post('preview', 'withdrawSubmit')->name('.submit');
            Route::get('history', 'withdrawLog')->name('.history');
        });

    });

    //Deposit
    Route::prefix('deposit')->controller('Gateway\PaymentController')->group(function () {
        Route::any('/deposit', 'deposit')->name('deposit');
        Route::post('deposit/insert', 'depositInsert')->name('deposit.insert');
        Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
        Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
        Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
    });

});



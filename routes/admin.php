<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    // Admin Login and Logout Process
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->middleware('admin')->name('logout');
    });

    // Admin Forgot Password and Verification Process
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'requestForm')->name('request.form');
        Route::post('forgot', 'sendResetCode');
        Route::get('verification/form', 'verificationForm')->name('code.verification.form');
        Route::post('verification/form', 'verificationCode');
    });

    // Admin Reset Password
    Route::controller('ResetPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset/form/{email}/{code}', 'resetForm')->name('reset.form');
        Route::post('reset', 'resetPassword')->name('reset');
    });
});

//Admin
Route::middleware('admin')->group(function () {

    //admin
    Route::controller('AdminController')->group(function () {

        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordChange');

        //Notification
        Route::name('system.notification.')->prefix('notification')->group(function () {
            Route::get('all', 'notificationAll')->name('all');
            Route::get('read/{id}', 'notificationRead')->name('read');
            Route::get('read-all', 'notificationReadAll')->name('read.all');
            Route::post('remove/{id}', 'notificationRemove')->name('remove');
            Route::post('remove-all', 'notificationRemoveAll')->name('remove.all');
        });

        //Trx
        Route::get('transaction', 'transaction')->name('transaction.index');

        //File Download
        Route::get('file-download', 'fileDownload')->name('file.download');
    });

    //Manage Users
    Route::controller('UserManagementController')->name('user.')->prefix('user')->group(function () {

        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');

        Route::get('kycv-pending', 'kycPending')->name('kyc.pending');
        Route::get('kyc-unconfirmed', 'kycUnConfirmed')->name('kyc.unconfirmed');

        Route::get('email-verification/unconfirmed', 'emailUnConfirmed')->name('email.unconfirmed');
        Route::get('mobile-verification/unconfirmed', 'mobileUnConfirmed')->name('mobile.unconfirmed');

        //KYC
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-cancel/{id}', 'kycCancel')->name('kyc.cancel');

        //Update
        Route::get('details/{id}', 'details')->name('details');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('balance-update/{id}', 'balanceUpdate')->name('add.sub.balance');
        Route::post('status/{id}', 'status')->name('status');
    });

    //Category 
    Route::controller('CategoryController')->prefix('manage/category')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });

    //Cattle Category
    Route::controller('CattleCategoryController')->prefix('manage/cattle/category')->name('cattle.category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });

    //Manage Accounts 
    Route::name('account.')->group(function () {
        Route::controller('AccountHeadController')->prefix('manage/account/head')->name('head.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
        });
        Route::controller('AccountSubHeadController')->prefix('manage/account/sub-head')->name('sub_head.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
        });
        Route::controller('GeneralExpenseController')->prefix('manage')->name('gen_expns.')->group(function () {
            Route::get('expenses/{val}', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
        });
    });

    //Manage Common System 
    Route::name('common.')->group(function () {
        Route::controller('WarehouseController')->prefix('manage/warehouse')->name('warehouse.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
        });
        Route::controller('ItemController')->prefix('manage/item')->name('item.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('view/{id}', 'view')->name('view');
        });
    });
    Route::controller('SupplierController')->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');
    });


    Route::controller('InventoryController')->prefix('manage/inventory')->name('inventory.')->group(function () {
        Route::get('/stocks/{val}', 'stockIndex')->name('stock.index');
        Route::get('/stock/history/{val}', 'stockHistory')->name('wh.stock.history');
        Route::get('stock/create/{val}', 'create')->name('stock.create');
        Route::post('stock/store', 'store')->name('stock.store');
        Route::get('wh/stock/update/{val}/{id}', 'stockQntEdit')->name('stock.edit');
        Route::get('/issues', 'issueIndex')->name('issue.index');
        Route::get('issue/create/{val}/{id}', 'issueCreate')->name('issue.create');
        Route::post('issue/store', 'issueStore')->name('issue.store');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('stock/edit/{val}/{id}', 'stockEdit')->name('inventory.stock.edit');
        Route::get('stock/history/{val}/{id}', 'stockHistoryEdit')->name('inventory.stock.history');
        Route::post('stock/history/update/{id}', 'stockHistoryUpdate')->name('inventory.stock.history.update');
    });

    //Cattle 
    Route::controller('CattleController')->prefix('manage/cattle')->name('cattle.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::get('details/{id}', 'details')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('update/ask-price/{id}', 'updateAskPrice')->name('update.askprice');
        Route::get('edit/weight/{id}', 'editWeight')->name('edit_weight');
        Route::post('update/weight/{id}', 'updateWeight')->name('update_weight');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('image-delete/{id}', 'cattleImageDelete')->name('image.delete');
    });

    //Customer
    Route::controller('CustomerController')->prefix('manage/customer')->name('customer.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('view/{id}', 'view')->name('view');
    });


    //Booking 
    Route::controller('BookingController')->prefix('manage/booking')->name('booking.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/booking/{id}', 'delete')->name('delete.booking');
        Route::post('/bookings/cancel', 'BookingCancel')->name('cancel');
    Route::post('/bookings/undo',  'undoBooking')->name('undo');



        Route::get('view/{id}', 'view')->name('view');
        Route::get('number-search', 'bookingNumberSearch')->name('number.search');
        Route::get('customer-search', 'bookingNumberByCustomerSearch')->name('customer.search');
        Route::get('estimate-cost-on-delivery', 'estimateCostAndWeightOnDelivery')->name('estimate.cost.on.delivery');

        Route::get('payments/list/{id}', 'paymentList')->name('payment.list');


        Route::get('add/payments/{id}', 'addPayment')->name('add.payment');
        Route::post('payments/store', 'storePayment')->name('store.payment');
        Route::put('payments/update', 'updatePayment')->name('update.payment');
        // Route::get('payments/edit/{id}/{bookingId}', 'editPayment')->name('edit.payment');

        Route::get('/admin/booking/{booking}/payment/{payment}/edit', 'editPayment')->name('edit.payment');


        Route::get('rtefund/{id}', 'refundPayment')->name('refund.payment');
        Route::post('rtefund/store', 'refundPaymentStore')->name('store.refund.payment');

        Route::get('delivery/print/challan/{id}', 'printChallan')->name('delivery.print');
        Route::post('delivery/challan/printing/{id}', 'challanPrinted')->name('delivery.challan.print');
        Route::get('delivery/index', 'delivery')->name('delivery.index');
        Route::get('delivery/index/{id}', 'deliveryEdit')->name('delivery.edit');
        Route::post('delivery/update/{id}', 'deliveryUpdate')->name('delivery.update');
        Route::get('cattles/delivering/{id}', 'cattleDelivered')->name('cattles.delivery');
        Route::post('cattles/print/print', 'Print_cattle')->name('cattle.print.print');
        Route::post('payment/slip/{id}', 'paymentSlip')->name('payment.slip');
    });

    // Booking Payments
    Route::controller('BookingPaymentController')->prefix('manage/cattle-booking-payment')->name('booking.payment.')->group(function () {
        Route::get('/{id}', 'index')->name('index');
        Route::get('/create/{id}', 'create')->name('create');
        Route::post('store/{id}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
    });

    //Admin 
    Route::controller('AdminController')->prefix('manage/admin')->name('admin.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    //role
    Route::controller('RoleController')->prefix('role')->name('role.')->group(function () {
        Route::get('/list', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    //Admin Roles
    Route::controller('AdminRoleController')->prefix('role')->name('role.')->group(function () {
        Route::get('admin-list', 'index')->name('list');
        Route::get('set-role/{id}', 'setRoles')->name('set');
        Route::post('admin-role-update/{id}', 'setUpdateRoles')->name('set.update');
    });

    //Role & Permission
    Route::controller('PermissionController')->prefix('role/permission')->name('role.permission.')->group(function () {
        Route::get('role-list', 'index')->name('list');
        Route::get('set-permission/{id}', 'setRolePermissions')->name('set');
        Route::post('role-permission-update/{id}', 'setUpdateRolePermissions')->name('set.update');
    });



    Route::name('gateway.')->prefix('gateway')->group(function () {

        //Automated
        Route::controller('GateAutomatorController')->prefix('automated')->name('automated.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });

        //Manual
        Route::controller('GateManualController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'new')->name('new');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    //Deposit
    Route::controller('DepositController')->prefix('manage/deposits')->name('deposit.')->group(function () {
        Route::get('/', 'deposit')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');

        Route::post('reject/{id}', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });

    // Withdrawal
    Route::name('withdraw.')->prefix('withdraw')->group(function () {

        //Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store/{id?}', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
        });

        // Withdrawal Management
        Route::controller('WithdrawController')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('Rejected', 'Rejected')->name('rejected');
            Route::post('approve', 'approve')->name('approve');
            Route::post('cancel', 'cancel')->name('cancel');
        });
    });


    //Contact & Subscriber
    Route::controller('ContactController')->group(function () {
        //subscriber
        Route::prefix('subscriber')->name('subscriber.')->group(function () {
            Route::get('/', 'subscriberIndex')->name('index');
            Route::post('remove/{id}', 'subscriberRemove')->name('remove');
            Route::post('send-email', 'sendEmailSubscriber')->name('send.email');
        });

        // contact
        Route::prefix('contact')->name('contact.')->group(function () {
            Route::get('/', 'contactIndex')->name('index');
            Route::post('remove/{id}', 'contactRemove')->name('remove');
            Route::post('status/{id}', 'contactStatus')->name('status');
        });
    });

    // Setting
    Route::controller('SettingController')->group(function () {

        Route::prefix('setting')->group(function () {
            // Basic Setting
            Route::get('basic', 'basic')->name('basic.setting');
            Route::post('basic', 'basicUpdate');
            Route::post('system', 'systemUpdate')->name('basic.system.setting');
            Route::post('logo-favicon', 'logoFaviconUpdate')->name('basic.logo.favicon.setting');

            //Plugins Setting
            Route::get('plugin', 'plugin')->name('plugin.setting');
            Route::post('plugin/update/{id}', 'pluginUpdate')->name('plugin.setting.update');
            Route::post('plugin/status/{id}', 'pluginStatus')->name('plugin.status');

            //SEO
            Route::get('seo', 'seo')->name('seo.setting');

            //KYC
            Route::get('kyc', 'kyc')->name('kyc.setting');
            Route::post('kyc', 'kycUpdate');
        });

        // Cookie
        Route::get('cookie', 'cookie')->name('cookie.setting');
        Route::post('cookie', 'cookieUpdate');

        // Maintenance
        Route::get('maintenance', 'maintenance')->name('maintenance.setting');
        Route::post('maintenance', 'maintenanceUpdate');

        // Cache Clear
        Route::get('cache-clear', 'cacheClear')->name('cache.clear');
    });

    // Email & SMS Setting
    Route::controller('NotificationController')->prefix('notification')->name('notification.')->group(function () {
        // Template Setting
        Route::get('versatile', 'versatile')->name('versatile');
        Route::post('versatile', 'versatileUpdate');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        // Email Setting
        Route::get('email/setting', 'email')->name('email');
        Route::post('email/setting', 'emailUpdate');
        Route::post('email/test', 'testEmail')->name('email.test');

        // SMS Setting
        Route::get('sms/setting', 'sms')->name('sms');
        Route::post('sms/setting', 'smsUpdate');
        Route::post('sms/test', 'testSMS')->name('sms.test');
    });

    // Language Setting
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('keywords', 'keywords')->name('keywords');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
        Route::get('translate/keyword/{id}', 'translateKeyword')->name('translate.keyword');
        Route::post('import', 'languageImport')->name('import.lang');
        Route::post('store/key/{id}', 'languageKeyStore')->name('store.key');
        Route::post('update/key/{id}', 'languageKeyUpdate')->name('update.key');
        Route::post('delete/key/{id}', 'languageKeyDelete')->name('delete.key');
    });

    // Manage Frontend
    Route::controller('SiteController')->prefix('site')->name('site.')->group(function () {
        Route::get('themes', 'themes')->name('themes');
        Route::post('themes', 'makeActive');
        Route::get('sections/{key}', 'sections')->name('sections');
        Route::post('content/{key}', 'content')->name('sections.content');
        Route::get('element/{key}/{id?}', 'element')->name('sections.element');
        Route::post('remove/{id}', 'remove')->name('remove');
    });
});

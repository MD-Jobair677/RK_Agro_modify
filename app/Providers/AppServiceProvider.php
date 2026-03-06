<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Contact;
use App\Models\Deposit;
use App\Models\SiteData;
use App\Models\Withdrawal;
use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        $setting                        = bs();
        $activeTheme                    = activeTheme();
        $shareToView['setting']         = $setting;
        $shareToView['activeTheme']     = $activeTheme;
        $shareToView['activeThemeTrue'] = activeTheme(true);
        $shareToView['emptyMessage']    = 'No data found';

        view()->share($shareToView);

        view()->composer('admin.partials.topbar', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', ManageStatus::NO)->with('user')->latest()->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', ManageStatus::NO)->count(),
            ]);
        });

        view()->composer('admin.partials.sidebar', function ($view) {
            $view->with([
                'bannedUsersCount'            => User::banned()->count(),
                'emailUnconfirmedUsersCount'  => User::emailUnconfirmed()->count(),
                'mobileUnconfirmedUsersCount' => User::mobileUnconfirmed()->count(),
                'kycUnconfirmedUsersCount'    => User::kycUnconfirmed()->count(),
                'kycPendingUsersCount'        => User::kycPending()->count(),
                'unansweredContactsCount'     => Contact::where('status', ManageStatus::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = SiteData::where('data_key', 'seo.data')->first();

            $view->with([
                'seo' => $seo ? $seo->data_info : $seo,
            ]);
        });


        if ($setting->enforce_ssl) URL::forceScheme('https');

        Paginator::useBootstrapFour();

    }
}

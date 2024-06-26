<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\Advertisement;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Merchant;
use App\Models\Page;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTP', true);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        try {
            $general = getGeneralSettings();
            $viewShare = $this->getViewShare($general);
            view()->share($viewShare);
            $this->registerComposerEvents();
            $this->enforceSsl($general);
        }
        catch (\Exception $exception) {
            Log::error('error from app service provider: ' . $exception->getMessage());
        }

        Paginator::useBootstrap();

    }

    /**
     * @return void
     */
    public function registerComposerEvents(): void //todo remove this method and put the data in the relevant controllers
    {
        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count' => User::banned()->count(),
                'email_unverified_users_count' => User::emailUnverified()->count(),
                'sms_unverified_users_count' => User::smsUnverified()->count(),
                'banned_merchants_count' => Merchant::banned()->count(),
                'email_unverified_merchants_count' => Merchant::emailUnverified()->count(),
                'sms_unverified_merchants_count' => Merchant::smsUnverified()->count(),
                'pending_user_ticket_count' => SupportTicket::where('user_id', '!=', 0)->whereIN('status', [0, 2])->count(),
                'pending_merchant_ticket_count' => SupportTicket::where('merchant_id', '!=', 0)->whereIN('status', [0, 2])->count(),
                'pending_deposits_count' => Deposit::pending()->count(),
                'pending_withdraw_count' => Withdrawal::pending()->count(),
                'live_product_count' => Product::live()->count(),
                'pending_product_count' => Product::pending()->count(),
                'upcoming_product_count' => Product::upcoming()->count(),
                'expired_product_count' => Product::expired()->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('read_status', 0)->with('user')->orderBy('id', 'desc')->limit(10)->get(),
                'adminNotificationsCount' => AdminNotification::where('read_status', 0)->with('user')->orderBy('id', 'desc')->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });
    }

    /**
     * @param $general
     * @return void
     */
    public function enforceSsl($general): void
    {
        if ($general->force_ssl) {
            \URL::forceScheme('https');
        }
    }

    /**
     * @param $general
     * @return array
     */
    public function getViewShare($general): array
    {
        $activeTemplate = activeTemplate();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = getLanguages();
        $viewShare['pages'] = getTemplatePages($activeTemplate);
        return $viewShare;
    }


}

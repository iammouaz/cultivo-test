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
use App\Services\BidService;
use App\Services\EventService;
use App\Services\OfferService;
use App\Services\OfferSheetService;
use App\Services\ProductService;
use App\Services\Repositories\AutoBidSettingRepository;
use App\Services\Repositories\BidRepository;
use App\Services\Repositories\CategoryRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\EventRepository;
use App\Services\Repositories\OfferRepository;
use App\Services\Repositories\OfferSheetRepository;
use App\Services\Repositories\OriginRepository;
use App\Services\Repositories\ProductRepository;
use App\Services\Repositories\RegionRepository;
use App\Services\Repositories\ShippingRangesRepository;
use App\Services\Repositories\ShippingRegionRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class BusinessLayerServiceProvider extends ServiceProvider implements DeferrableProvider//deferrable provider is used to defer the loading of the service provider until it is actually needed
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('categoryRepository', function ($app) {
            return new CategoryRepository($app);
        });
        $this->app->singleton('regionRepository', function ($app) {
            return new RegionRepository($app);
        });
        $this->app->singleton('countryRepository', function ($app) {
            return new CountryRepository($app);
        });
        $this->app->singleton('originRepository', function ($app) {
            return new OriginRepository($app);
        });
        $this->app->singleton('eventRepository', function ($app) {
            return new EventRepository($app);
        });
        $this->app->singleton('shippingRegionRepository', function ($app) {
            return new ShippingRegionRepository($app);
        });
        $this->app->singleton('shippingRangesRepository', function ($app) {
            return new ShippingRangesRepository($app);
        });
        $this->app->singleton('autoBidSettingRepository', function ($app) {
            return new AutoBidSettingRepository($app);
        });
        $this->app->singleton('bidRepository', function ($app) {
            return new BidRepository($app);
        });
        $this->app->singleton('productRepository', function ($app) {
            return new ProductRepository($app);
        });
        $this->app->singleton('eventService', function ($app) {
            return new EventService($app);
        });
        $this->app->singleton('bidService', function ($app) {
            return new BidService($app);
        });
        $this->app->singleton('productService', function ($app) {
            return new ProductService($app);
        });
//        if(config('app.COMMERCE_MODE')){
            $this->app->singleton('offerService', function ($app) {
                return new OfferService($app);
            });
            $this->app->singleton('offerSheetService', function ($app) {
                return new OfferSheetService($app);
            });
            $this->app->singleton('offerRepository', function ($app) {
                return new OfferRepository($app);
            });
            $this->app->singleton('offerSheetRepository', function ($app) {
                return new OfferSheetRepository($app);
            });
//        }

    }
    public function provides()
    {
        return [
            'categoryRepository',
            'regionRepository',
            'countryRepository',
            'eventRepository',
            'shippingRegionRepository',
            'shippingRangesRepository',
            'autoBidSettingRepository',
            'bidRepository',
            'productRepository',
            'eventService',
            'bidService',
            'productService',
            'offerService',
            'offerSheetService',
            'offerRepository',
            'offerSheetRepository',
        ];

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}

<?php

namespace App\Services;

use App\Services\Repositories\AutoBidSettingRepository;
use App\Services\Repositories\BidRepository;
use App\Services\Repositories\CategoryRepository;
use App\Services\Repositories\CountryRepository;
use App\Services\Repositories\EventRepository;
use App\Services\Repositories\ProductRepository;
use App\Services\Repositories\OfferSheetRepository;
use App\Services\Repositories\OfferRepository;
use App\Services\Repositories\RegionRepository;
use App\Services\Repositories\ShippingRangesRepository;
use App\Services\Repositories\ShippingRegionRepository;
use App\Services\Repositories\OriginRepository;

abstract class BaseService
{
    /**
     * @var int
     */
    protected $paginationSize;
    /**
     * @var RegionRepository
     */
    protected $regionRepository;
    /**
     * @var ShippingRegionRepository
     */
    protected $shippingRegionRepository;
    /**
     * @var ShippingRangesRepository
     */
    protected $shippingRangesRepository;
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;
    /**
     * @var CountryRepository
     */
    protected $countryRepository;
    /**
     * @var AutoBidSettingRepository
     */
    protected $autoBidSettingRepository;
    /**
     * @var BidRepository
     */
    protected $bidRepository;
    /**
     * @var EventRepository
     */
    protected $eventRepository;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var OfferSheetRepository
     */
    protected $offerSheetRepository;
    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    /**
     * @var OriginRepository
     */
    protected $originRepository;

    public function __construct($app)
    {
        $this->paginationSize = getPaginate();
        $this->regionRepository = $app->regionRepository;
        $this->categoryRepository = $app->categoryRepository;
        $this->countryRepository = $app->countryRepository;
        $this->eventRepository = $app->eventRepository;
        $this->shippingRegionRepository = $app->shippingRegionRepository;
        $this->shippingRangesRepository = $app->shippingRangesRepository;
        $this->autoBidSettingRepository = $app->autoBidSettingRepository;
        $this->bidRepository = $app->bidRepository;
        $this->productRepository = $app->productRepository;
//        if(config('app.COMMERCE_MODE')) {
            $this->offerSheetRepository = $app->offerSheetRepository;
            $this->offerRepository = $app->offerRepository;
//        }
        $this->originRepository=$app->originRepository;
    }


}

<?php

namespace App\Services;

use App\Exceptions\EventValidationException;
use App\Exceptions\PushNotificationException;
use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Jobs\CloseEvent;
use App\Models\Category;
use App\Models\Country;
use App\Models\DefaultRegions;
use App\Models\Event;
use App\Models\Fee;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\SampleSet;
use App\Models\ShippingRanges;
use App\Models\ShippingRegion;
use App\Models\User;
use App\Models\UserEvent;
use App\Models\UserRequest;
use App\Models\Winner;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Sentry\Laravel\Integration;

class EventService extends BaseService
{
    /**
     * @param $with
     * @param $paginate
     * @return LengthAwarePaginator
     */
    public function latestPaginatedWith($with = [], $paginate = null)
    {
        $paginate ??= $this->paginationSize;
        return $this->eventRepository->query()->with($with)->latest()->paginate($paginate);
    }
    public function getById($id, $with = [])
    {
        return $this->eventRepository->query()->with($with)->where('id', $id)->first();
    }
    public function getCategories()
    {
        return $this->categoryRepository->getAll();
    }
    public function getRegions()
    {
        return $this->regionRepository->getAll();
    }
    public function getCountries()
    {
        return $this->countryRepository->getAll();
    }
    public function search($search)
    {
        return $this->eventRepository->query()->where(function ($event) use ($search) {
            $event->where('name', 'like', "%$search%");
        })->paginate($this->paginationSize);
    }
    public function getShippingRegions()
    {
        return $this->shippingRegionRepository->getAll();
    }
    public function getRangesArray($shippingRegions)
    {
        $rangesArray = [];
        foreach ($shippingRegions as $region) {
            foreach ($region->shippingRanges as $range) {
                $rangesArray[$region->id][$range->id]['from'] = $range->from;
                $rangesArray[$region->id][$range->id]['up_to'] = $range->up_to;
                $rangesArray[$region->id][$range->id]['cost'] = $range->cost;
            }
        }
        return $rangesArray;
    }

    public function saveEvent($request, $event) //todo use only validated request items
    {

        if ($request->hasFile('image')) {
            try {
                $event->image = uploadImageToS3($request->image, imagePath()['event']['path'], imagePath()['event']['size'], $event->image, imagePath()['event']['thumb']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new EventValidationException(__('Image could not be uploaded.'));
            }
        }
        if ($request->hasFile('logo')) {
            try {
                $event->logo = uploadImageToS3($request->logo, imagePath()['event_logo']['path'], imagePath()['event_logo']['size'], $event->logo, imagePath()['event_logo']['thumb']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new EventValidationException(__('Image could not be uploaded.'));
            }
        } elseif (!$request->hasFile('logo') && $event->logo) {
            $event->logo = null;
        }

        if ($request->hasFile('banner_image')) {
            try {
                $event->banner_image = uploadImageToS3(
                    $request->banner_image,
                    imagePath()['event_banner_image']['path'],
                    imagePath()['event_banner_image']['size'],
                    $event->banner_image,
                    imagePath()['event_banner_image']['thumb'],
                    true,
                    imagePath()['event_banner_image']['size_sm'],
                    imagePath()['event_banner_image']['size_md']
                );
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new EventValidationException(__('Image could not be uploaded.'));
            }
        }

//        if ($request->event_type == 'ace_event') {
//            $event->banner_image = 'Coffee-beans-on-the-tree.jpg';
//        }

        $event->name = $request->name;
        $event->practice = $request->practice;
        $event->sname = $request->sname;
        $event->description = $request->description;
        $event->start_date = $request->start_date;
        $event->display_end_date = $request->display_end_date;
        $event->end_date = $request->end_date;
        $event->max_end_date = $request->max_end_date;
        $event->less_bidding_time = $request->less_bidding_time;
        $event->event_type = 'm_cultivo_event'; //$request->event_type ?? ;
        $event->deposit = $request->deposit;
        $event->agreement = $request->agreement;
        $event->EventClockStartOn = $request->EventClockStartOn;
        $event->category_id =   $request->category ?? Category::firstOrCreate(['name' => 'Auction'])->id;
        $event->max_bidding_value = $request->max_bidding_value;
        $event->bid_status = $request->bid_status;
        $event->emails = $request->emails;
        $event->show_sample_set_button = $request->show_sample_set_button ?? 0;
        $event->sample_set_button_lable = $request->sample_set_button_lable;
        $event->sample_set_external_url = $request->sample_set_external_url;
        $event->sample_set_cart_config = $request->sample_set_cart_config;
        $event->hero_show_action_name = $request->hero_show_action_name ?? 0;
        $event->hero_text_color = json_encode($request->hero_text_color ?? []);
        $event->hero_primary_button_color = json_encode($request->hero_primary_button_color ?? []);
        $event->hero_image_overlay = json_encode($request->hero_image_overlay ?? []);
        $event->hero_outlined_button_color = json_encode($request->hero_outlined_button_color ?? []);
        $event->login_type = $request->login_type ;
        //remove the app_url from the beginning of the url
        $url = $request->url;
        if ($url) {
            $url = str_replace(config('app.url') . '/', '', $url);
        } else {
            do {
                $url = random_int(100000, 999999);
            } while (Event::query()->where('event_url', $url)->exists()); //although unlikely to happen but this is safer
        }
        $event->event_url = $url;
        $event->sample_set_limit_per_account = $request->sample_set_limit_per_account ?? 0;
        $event->save();
        //
        if ($request->sample_set_cart_config == 'payment_process' || $request->sample_set_cart_config == 'orders_by_email') {
            // if doesn't have sample set or if the price or weight or number of samples per box or weight per sample has changed  create new sample set
            $sample_set = $event->samplesets()->get()->last()??null;
            if ($sample_set == null || $sample_set->price != $request->sample_set_price || $sample_set->total_package_weight_Lb != $request->total_package_weight_Lb || $sample_set->number_of_samples_per_box != $request->number_of_samples_per_box || $sample_set->weight_per_sample_grams != $request->weight_per_sample_grams ) {
              $sample_set=  $this->createSampleSet($request, $event);
            }else{
                if ($request->hasFile('sample_set_image')) {
                    try {
                        $sample_set->image = uploadImageToS3($request->sample_set_image,
                            imagePath()['product']['path'], imagePath()['product']['size'],
                            $sample_set->image, imagePath()['product']['thumb']);
                    } catch (\Exception $exp) {
                        Log::error($exp);
                        Integration::captureUnhandledException($exp);
                        throw new EventValidationException(__('Image could not be uploaded.'));
                    }
                }
            }


        }
        $this->addEventCloseJob($event);
        clear_all_cache();
    }
    public function createSampleSet($request, $event)
    {
        $sample_set = new SampleSet();
        $sample_set->event_id = $event->id;
        $sample_set->price = $request->sample_set_price;
        $sample_set->total_package_weight_Lb = $request->total_package_weight_Lb;
        $sample_set->number_of_samples_per_box = $request->number_of_samples_per_box;
        $sample_set->weight_per_sample_grams = $request->weight_per_sample_grams;
        if ($request->hasFile('sample_set_image')) {
            try {
                $sample_set->image = uploadImageToS3($request->sample_set_image, imagePath()['product']['path'], imagePath()['product']['size'], $sample_set->image, imagePath()['product']['thumb']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new EventValidationException(__('Image could not be uploaded.'));
            }
        }else{
            $sample_set->image = $request->sample_set_image;
        }

        $sample_set->save();
        return $sample_set;
    }

    public function saveFee($request, $event) //todo use only validated request items
    {
        if ($request->has('fees')) {
            foreach ($request->fees as $key => $element) {
                $fee = new Fee();
                $fee->event_id = $event->id;
                $fee->event_type = get_class($event);
                $fee->country_id = $element["country_id"];
                $fee->fee_value = $element["fee_value"];
                $fee->payment_method = $element["payment_method"];
                $fee->save();
            }
        }
    }
    public function saveregion($request, $event) //todo use only validated request items
    {
        if ($request->shippingregions) {
            foreach ($request->shippingregions as $key => $oneregion) {
                $shippingregion = new ShippingRegion();

                $shippingregion->event_id = $event->id;
                $shippingregion->event_type = get_class($event);
                $shippingregion->region_name = $oneregion['region_name'];
                $shippingregion->shipping_method = $oneregion['shipping_method'];
                $shippingregion->save();
                foreach ($request->shippingranges[$key] as $onerange) {

                    $shippingranges = new ShippingRanges();
                    $shippingranges->region_id = $shippingregion->id;
                    $shippingranges->from = $onerange['from'];
                    $shippingranges->up_to = $onerange['up_to'];
                    $shippingranges->cost = $onerange['cost'];
                    $shippingranges->save();
                }
            }
        }
    }
    public function addEvent($request)
    {

        $this->validation($request, 'required');
        if (Carbon::parse($request->end_date)->diffInMinutes(Carbon::parse($request->start_date)) < $request->less_bidding_time) {
            throw new EventValidationException(__('Less Bidding Time must be Lesser than Event Period'));
        }
        $event = new Event();
        DB::transaction(function () use ($request, $event) {
            $this->saveEvent($request, $event);
            $this->saveregion($request, $event);
            $this->saveFee($request, $event);
        });
        return $event;
    }
    protected function validation($request, $imgValidation)
    {
        if ($request->sample_set_cart_config == 'payment_process' || $request->sample_set_cart_config == 'orders_by_email'){

            $request->validate([
                'sample_set_price' => 'required',
                'total_package_weight_Lb' => 'required',
                'number_of_samples_per_box' => 'required',
                'weight_per_sample_grams' => 'required',
                'sample_set_limit_per_account' => 'required',
                'sample_set_image' => "$imgValidation|image|mimes:jpeg,png,jpg",
            ]);
        }
        $request->validate([
            'name' => 'required',
            // 'category' => 'required|exists:categories,id',
            'description' => 'required',
            'end_date' => 'required',
            'agreement' => 'required',
            'display_end_date' => 'nullable|after:start_date',
            'max_end_date' => 'nullable|after:end_date',
            'less_bidding_time' => 'required',
            'deposit' => 'required',
            'start_date' => 'required|date|before:end_date',
            'max_bidding_value' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'image' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'logo' => ['nullable', 'image', new FileTypeValidate([
                'jpeg', 'jpg',
                'png'
            ])],
            'banner_image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'show_sample_set_button' => 'boolean',
            'sample_set_button_lable' => 'nullable|string',
            'sample_set_external_url' => 'nullable|string',
            'sample_set_cart_config' => 'nullable|string|in:payment_process,external_url,orders_by_email',
            'hero_show_action_name' => 'boolean | nullable',
            'hero_text_color' => 'nullable',
            'contained_button_color' => 'nullable',
            'outlined_button_color' => 'nullable',
            'hero_image_overlay' => 'nullable',
            'url' => 'required|unique:events,event_url,' . $request->input('id'),
            'login_type' => 'nullable|string',
        ]);
    }
    public function editEvent($request, $id)
    {
        $this->validation($request, 'nullable');
        //$is_new_bid_defrent = $event->bid_status != $request->bid_status;
        if (Carbon::parse($request->end_date)->diffInMinutes(Carbon::parse($request->start_date)) < $request->less_bidding_time) {
            throw new EventValidationException(__('Less Bidding Time must be Lesser than Event Period'));
        }
        $event = $this->getById($id, ['products']);
        if (is_null($event)) {
            throw new EventValidationException(__('Event not found'));
        }
        if ($request->sample_set_cart_config == 'payment_process' || $request->sample_set_cart_config == 'orders_by_email') {
            // if doesn't have sample set or if the price or weight or number of samples per box or weight per sample has changed  create new sample set
            $sample_set = $event->samplesets()->get()->last() ?? null;
            $image = $sample_set->image ?? null;
            $request->sample_set_image ??= $image;
//            dd($request->sample_set_image ,$image);
            if($image == null || $sample_set ==null){
                $request->validate([
                    'sample_set_image' => "required|image|mimes:jpeg,png,jpg",
                ]);
            }
        }

        DB::transaction(function () use ($request, $event, $id) {
            $this->saveEvent($request, $event);
            $this->updateregion($request, $event, $id);
            $this->updateFee($request, $event);
        });
        try {
            clear_all_cache();

            event(new EventPush($id));
            //            if ($is_new_bid_defrent && $event->end_date > now()) {
            foreach ($event->products as $product) {
                event(new ProductPush($product->id));
            }
            //            }
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
            throw new PushNotificationException(__('Error in sending push notification'));
        }
    }
    public function UpdateFee($request, $event)
    {
        $event->fees()->delete();
        if ($request->has('fees')) {
            $this->saveFee($request, $event);
        }
    }

    public function updateregion($request, $event, $id)
    {

        if ($request->shippingregions) {
            $this->shippingRangesRepository->query()->whereHas('region', function ($query) use ($id) {
                $query->whereHas('event', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            })->delete();
            $this->shippingRegionRepository->query()->whereHas('event', function ($query) use ($id) {
                $query->where('id', $id);
            })->delete();


            foreach ($request->shippingregions as $key => $oneregion) {
                $shippingregion = new ShippingRegion();

                $shippingregion->event_id = $event->id;
                $shippingregion->event_type = get_class($event);
                $shippingregion->region_name = $oneregion['region_name'];
                $shippingregion->shipping_method = $oneregion['shipping_method'];
                $shippingregion->save();
                foreach ($request->shippingranges[$key] as $onerange) {

                    $shippingranges = new ShippingRanges();
                    $shippingranges->region_id = $shippingregion->id;
                    $shippingranges->from = $onerange['from'];
                    $shippingranges->up_to = $onerange['up_to'];
                    $shippingranges->cost = $onerange['cost'];
                    $shippingranges->save();
                }
            }
        }
    }

    public function endEvent($id, $agree_end_event)
    {
        $now = Carbon::now();
        $event = DB::transaction(function () use ($id, $now, $agree_end_event) {
            $event = $this->eventRepository->query()->where('id', $id)->with(['products'])->lockForUpdate()->first();
            $products = $event->products;

            $is_event_ended = $event->status == 'ended';

            if ($is_event_ended) {
                throw new EventValidationException(__('This event has already ended'));
            }

            $max_bids = [];
            foreach ($products as $product) { //todo optimize query use joins
                $max_bids[$product->id] = $product->bids()->with(['user', 'product'])->orderBy('amount', 'desc')->first();
            }

            //check if all the products have at least one bid
            $check_product_bids = true;
            foreach ($max_bids as $max_bid) {
                if (is_null($max_bid)) {
                    $check_product_bids = false;
                }
            }

            //end the event only if the all the products have a bid
            if (!$check_product_bids && !$agree_end_event) {
                throw new EventValidationException(__('you can\'t end an event before all products have bid on'));
            }
            //Make the products expired
            foreach ($products as $product) {
                $product->expired_at = $now;
                $product->save(); //todo use bulk edit
            }

            $winners = [];
            //Define the winner of the bid
            foreach ($max_bids as $key => $max_bid) {
                if (is_null($max_bid)) {
                    continue;
                }
                $winner = [];
                $winner['user_id'] = $max_bid->user_id;
                $winner['product_id'] = $key;
                $winner['bid_id'] = $max_bid->id;
                $winner['created_at'] = $now;
                $winner['updated_at'] = $now;
                $winners[] = $winner;
            }
            //Save the winners
            Winner::insert($winners);
            //Send email notifications to the winning users
            foreach ($max_bids as $max_bid) {
                if (is_null($max_bid)) {
                    continue;
                }

                $winning_user = $max_bid->user;
                $owned_product = $max_bid->product;
                try {
                    notify($winning_user, 'BID_WINNER', [ //todo notify users after the transaction commits
                        'product' => $owned_product->name . '',
                        'product_price' => 'US$' . round($owned_product->price, 2) . '/lb',
                        'currency' => '',
                        'amount' => 'US$' . round($max_bid->amount, 2) . '/lb'
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    Integration::captureUnhandledException($e);
                    throw new EventValidationException(__('Error while notifying user, event is not ended.'));
                }
            }


            //End the event
            $event->end_date = $now;
            $event->status = 'ended';
            $event->start_status = "ended";
            $event->bid_status = "closed";
            $event->save();

            return $event;
        });

        try {
            clear_all_cache();

            event(new EventPush($id));
            foreach ($event->products as $product) {
                event(new ProductPush($product->id));
            }
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
            throw new PushNotificationException(__('Error in sending push notification'));
        }
        return $event;
    }
    public function checkIfAllProductsHaveBids($id) //todo optimize queries
    {
        $event = Event::findOrFail($id);
        $products = $event->products;


        $max_bids = [];
        foreach ($products as $product) {
            $max_bids[$product->id] = $product->bids()->orderBy('amount', 'desc')->first();
        }

        //check if all the products have at least one bid
        $check_product_bids = true;
        foreach ($max_bids as $key => $max_bid) {
            if (is_null($max_bid)) {
                $check_product_bids = false;
            }
        }
        return $check_product_bids;
    }
    public function setEndDate($id)
    { //todo use repository functon calls, add db migration with locking, eager load
        $event = $this->eventRepository->query()->where('id', $id)->with(['products'])->first();
        if (is_null($event)) {
            throw new EventValidationException(__('Event not found'));
        }
        if ($event->start_status == 'ended') {
            throw new EventValidationException(__('This event has already ended'));
        }

        if (isset($event->max_end_date)) {
            if ($event->max_end_date < now()) {
                throw new EventValidationException(__('you cannot start the clock because it exceeds the max date.'));
            }
            if ($event->max_end_date < now()->addMinutes($event->less_bidding_time)) {
                $event->end_date = $event->max_end_date;
            } else {
                $event->end_date = now()->addMinutes($event->less_bidding_time);
            }
        } else {
            $event->end_date = now()->addMinutes($event->less_bidding_time);
        }
        $event->start_status = "started";
        $is_closed = $event->bid_status == "closed";
        $event->bid_status = 'open';

        $event->start_counter = $event->start_counter + 1;
        $event->save();
        $this->addEventCloseJob($event);
        try {
            clear_all_cache();

            event(new EventPush($id));
            if ($is_closed && $event->end_date > now()) {
                foreach ($event->products as $product) {
                    event(new ProductPush($product->id));
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
            throw new PushNotificationException(__('Error in sending push notification'));
        }
    }
    public function addTestEvent($request) //todo use repository functon calls, bulk insert
    {
        DB::transaction(function () use ($request) {

            $event = new Event();
            $event->name = $request->name . '-Test';
            $category = Category::select('id')->where('name', 'Producer Auctions')->first();
            $event->category_id = $category->id;
            $event->practice = 1;
            $event->event_type = 'm_cultivo_event';
            $event->start_date = now();
            $event->display_end_date = now()->addMonth()->subDay();
            $event->end_date = now()->addMonth();
            $event->less_bidding_time = 2;
            $event->deposit = 11;
            $event->max_bidding_value = 100;
            $event->EventClockStartOn = 1;
            $event->description = $request->name . '-Test';
            $event->agreement = $request->name . '-Test';
            $event->bid_status = 'open';
            $event->image = 'm-cultivo-black-logo.png';
            $event->logo = 'm-cultivo-black-logo.png';
            $event->banner_image = 'm-cultivo-black-logo.png';
            //            $event->event_url = todo add url
            $event->save();
            $event->event_url = $event->id;
            $event->save();
            $this->addEventCloseJob($event);

            $shippingregion = new ShippingRegion();

            $shippingregion->event_id = $event->id;
            $shippingregion->event_type = get_class($event);
            $shippingregion->region_name = 'Rest of the World';
            $shippingregion->shipping_method = 'Ocean Freight';
            $shippingregion->save();

            $shippingranges = new ShippingRanges();
            $shippingranges->region_id = $shippingregion->id;
            $shippingranges->from = 1;
            $shippingranges->up_to = 10000;
            $shippingranges->cost = 1;
            $shippingranges->save();

            $fee = new Fee();
            $fee->event_id = $event->id;
            $fee->event_type = get_class($event);
            $fee->country_id = '999';
            $fee->fee_value = '5';
            $fee->payment_method = 'Credit Card';
            $fee->save();

            $specifications = ['Rank' => 0, 'Score' => 1, 'Size' => 1, 'Weight' => 5, 'Process' => __('Washed'), 'Variety' => __('Marsellesa')];
            $specs = [];
            $specs[0] = array('name' => 'Rank', 'value' => 0, 'is_display' => '1');
            $specs[1] = array('name' => 'Score', 'value' => 1, 'is_display' => '1');
            $specs[2] = array('name' => 'Size', 'value' => 1, 'is_display' => '1');
            $specs[3] = array('name' => 'Weight', 'value' => 5, 'is_display' => '1');
            $specs[4] = array('name' => 'Process', 'value' => __('Washed'), 'is_display' => '1');
            $specs[5] = array('name' => __('Variety'), 'value' => __('Marsellesa'), 'is_display' => '1');
            for ($i = 1; $i <= $request->products_count; $i++) {
                $specs[0]['value'] = $i;
                $product = new Product([
                    'name' => $event->name . 'product-' . $i,
                    'event_id' => $event->id,
                    'admin_id' => 1,
                    'merchant_id' => 0,
                    'price' => 1,
                    'status' => 1,
                    'less_bidding_value' => 0.01,
                    'started_at' => now(),
                    'expired_at' => now()->addMonth(),
                    'image' => 'm-cultivo-black-logo.png',
                    'specification' => $specs,
                ]);
                $product->save();
                foreach ($request->users as $user_id) {
                    UserEvent::create([
                        'user_id' => $user_id,
                        'product_id' => $product->id,
                        'event_id' => $event->id,
                        'is_active' => 1
                    ]);
                }
                foreach ($specifications as $key => $spec) {
                    $specification = new ProductSpecification();
                    $specification->product_id = $product->id;
                    $specification->spec_key = $key;
                    $specification->Value = $key == 'Rank' ? $i : $spec;
                    $specification->is_display = 1;
                    $specification->save();
                }
            }
            foreach ($request->users as $user_id) {
                UserRequest::create([
                    'user_id' => $user_id,
                    'event_id' => $event->id,
                    'status' => 1,
                    'terms_accept' => 1
                ]);
            }
        });
    }

    /**
     * @param $event
     * @return void
     */
    public function closeEvent($event): void
    {
        $event->bid_status = 'closed';
        $event->start_counter = $event->start_counter + 1;

        $event->save();

        try {
            clear_all_cache();
            event(new EventPush($event->id));
            foreach ($event->products as $product) {
                event(new ProductPush($product->id));
            }
        } catch (\Exception $e) { //todo log all errors in pusher events
            Log::error('cannot send push notification, details below\n' . $e);
            Integration::captureUnhandledException($e);
        }

        //send email to event emails list when the clock run out todo make job for sending these emails
        $emails = explode(',', $event->emails);
        foreach ($emails as $email) {
            sendEmail_v2($email, 'Clock_Notification', [
                'event_name' => $event->name
            ]);
        }
    }
    public function addEventCloseJob($event)
    {

        if ($event->bid_status == 'open') {
            $queueConnection = strtolower(config('app.event_close_queue_connection') ?? '');
            if ($queueConnection == '') {
                //                if (config('app.env') != 'production') {
                Log::info('The event close queue connection is not set in the config, consider setting "event_close_queue_connection" to "redis" or "database"');
                //                }
                return; //no value set in config just ignore the job creation
            } elseif ($queueConnection != 'redis') {
                Log::info('The event close queue connection is ' . $queueConnection . ', which is not supported, consider using "redis" or "database" queue connections.');
            } else {
                if (Carbon::parse($event->end_date) > now()) {
                    //                    CloseEvent::dispatch($event->id)->delay(Carbon::parse($event->end_date))->onConnection(config($queueConnection));
                    //                    $delay = Carbon::parse($event->end_date)->diffInSeconds(now());
                    $job = (new CloseEvent($event->id));
                    $connection = Queue::connection($queueConnection);
                    $connection->later(Carbon::parse($event->end_date), $job);
                    if (config('app.env') != 'production') {
                        Log::info('The event close job is dispatched to ' . $queueConnection . ' queue connection and delayed to ' . $event->end_date);
                    }
                } else {
                    CloseEvent::dispatch($event->id)->onConnection(config($queueConnection));
                    if (config('app.env') != 'production') {
                        Log::info('The event close job is dispatched to ' . $queueConnection . ' queue connection without delay because the end date is in the past at ' . $event->end_date);
                    }
                }
            }
        }
    }

    public function duplicateEvent($id)
    {
        $event = Event::find($id);
        $duplicate_event = $event->replicate();
        $duplicate_event->name = $event->name . ' - Copy';
        $duplicate_event->event_url = $event->event_url . '-copy';
        try {
            $duplicate_event->save();
        }
        catch (\Exception $e) {
            $duplicate_event->event_url = $event->event_url . '-copy-' . random_int(1000, 9999);
            $duplicate_event->save();
            $duplicate_event->event_url = $duplicate_event->id;
            $duplicate_event->save();
        }


        foreach ($event->shippingRegions as $region) {
            $duplicated_region = $duplicate_event->shippingRegions()->create($region->toArray());
            foreach ($region->shippingRanges as $range) {
                $duplicated_region->shippingRanges()->create($range->toArray());
            }
        }

        foreach ($event->fees as $fee) {
            $duplicate_event->fees()->create($fee->toArray());
        }

        foreach ($event->products as $product) {
            $duplicated_product = $duplicate_event->products()->create($product->toArray());
            foreach ($product->product_specification  as $spec) {
                $duplicated_product->product_specification()->create($spec->toArray());
            }
        }
    }

    public function deleteEvent($id)
    {

        $event = Event::find($id);
        if ($event->products()->count()) {
            foreach ($event->products as $product) {

                if ($product->product_specification()->count()) {
                    $product->product_specification()->delete();
                }
                $product->delete();
            }
        }

        if ($event->shippingRegions()->count()) {
            foreach ($event->shippingRegions as $region) {
                if ($region->shippingRanges()->count()) {
                    $region->shippingRanges()->delete();
                }
                $region->delete();
            }
        }
        if ($event->fees()->count()) {
            $event->fees()->delete();
        }

        $event->delete();
    }
}

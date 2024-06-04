<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\EventValidationException;
use App\Exceptions\PushNotificationException;
use App\Http\Controllers\Controller;
use App\Http\Helpers\PusherHelper;
use App\Models\Event;
use App\Models\OfferSheet;
use App\Models\SampleSet;
use App\Models\User;
use App\Services\EventService;
use App\Services\OfferSheetService;
use App\Services\Repositories\EventRepository;
use App\Services\Repositories\OfferSheetRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;

    /**
     * @var EventService
     */
    protected $eventService;
    /**
     * @var EventRepository
     */
    protected $eventRepository;

     /**
     * @var OfferSheetService
     */
    protected $offerSheetService;
    /**
     * @var OfferSheetRepository
     */
    protected $offerSheetRepository;
    public function __construct()
    {
        $this->eventService = app('eventService');
        $this->offerSheetService = app('offerSheetService');
        $this->eventRepository = app('eventRepository');
        $this->offerSheetRepository = app('offerSheetRepository');
    }
    // protected $search;


    public function index($type=null)
    {
        if($type=='auctions'){
            $events = $this->eventService->latestPaginatedWith(['category']);
        }elseif($type=='offerSheets'){

            $events = $this->offerSheetService->latestPaginatedWith(['category']);
        }else{
            $offerSheetCategories = $this->offerSheetService->getOfferSheetCategories()->pluck('id')->toArray();
            $perPage = getPaginate(20);
            $page = request()->get('page') ?? 1; // Get the current page or default to 1
            $offset = ($page * $perPage) - $perPage;
            $events1 = DB::select("select * from (select id,created_at,category_id from events-- where deleted_at is null
                                union all select id,created_at,category_id from offer_sheets where deleted_at is null) as t
                                order by created_at desc limit {$offset},{$perPage}");
            $count = DB::select("select count(*) as count from (select id,created_at,category_id from events-- where deleted_at is null
                                union all select id,created_at,category_id from offer_sheets where deleted_at is null) as t
                                ")[0]->count;
            $events1 = collect($events1);
            $events2 = $this->eventRepository->query()->whereIn('id', $events1->whereNotIn('category_id', $offerSheetCategories)->pluck('id'))->get();
            $offerSheets= $this->offerSheetRepository->query()->whereIn('id', $events1->whereIn('category_id', $offerSheetCategories)->pluck('id'))->get();
            $events = $events2->merge($offerSheets)->sortByDesc('created_at')->values()->all();
            $events = new LengthAwarePaginator($events, $count, $perPage, $page, ['path' => Paginator::resolveCurrentPath()]);
        }
        $pageTitle = __("All Events");
        $emptyMessage = __("No Events Found");
        return view('admin.event.index', compact('pageTitle', 'emptyMessage', 'events'));
    }


    public function create()
    {
        $categories = $this->eventService->getCategories();
        $regions = $this->eventService->getRegions();
        $countries = $this->eventService->getCountries();
        $pageTitle = __('Create event');
        return view('admin.event.create', compact('pageTitle', 'countries', 'regions', 'categories', 'countries'));
    }

    public function edit($id)
    {
        $pageTitle = __('Update Event');

        $event = $this->eventService->getById($id,['fees', 'shippingRegions', 'shippingRegions.shippingRanges']);
        $categories = $this->eventService->getCategories();
        $regions = $this->eventService->getRegions();
        $countries = $this->eventService->getCountries();
        $shippingregions = $event->shippingRegions;
        $fees = $event->fees;
        $rangesarray=$this->eventService->getRangesArray($shippingregions);
        $sample_set = SampleSet::with("event")->where('event_id', $event->id)
            ->orderby('created_at','desc')->first();

        return view('admin.event.edit', compact('pageTitle', 'countries', 'regions', 'event', 'fees', 'shippingregions', 'categories', 'rangesarray', 'sample_set'));
    }

    public function store(Request $request)
    {

        try {
            $this->eventService->addEvent($request);

        }
        catch (EventValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
            //will not fail the event creation
        }
        $notify[] = ['success', __('Event added successfully')];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->eventService->editEvent($request, $id);
        }
        catch (EventValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
            //will not fail the event update
        }
        $notify[] = ['success', __('Event updated successfully')];
        return back()->withNotify($notify);
    }


    public function endEvent($id, $agree_end_event = false)
    {
        try {
            $this->eventService->endEvent($id, $agree_end_event);
        }
        catch (EventValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
            //will not fail the event end
        }
        $notify[] = ['success', __('Event ended successfully')];

        return back()->withNotify($notify);
    }

    public function checkIfAllProductHasBid($id)
    {
        $check_product_bids = $this->eventService->checkIfAllProductsHaveBids($id);
        return response()->json(['is_all_has_bid' => $check_product_bids]);
    }
    public function set_end_date($id)
    {
        try {
            $this->eventService->setEndDate($id);
        }
        catch (EventValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
            //will not fail the event end
        }
        $notify[] = ['success', __('start successfully.')];
        return back()->withNotify($notify);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $events = $this->eventService->search($search);
        $pageTitle = 'Event Search - ' . $search;
        $emptyMessage = __('No search result found');
        if ($request->has('user_id')) {
            $id = $request->user_id;
            return view('admin.users.events', compact('pageTitle', 'search', 'emptyMessage', 'events', 'id'));
        }

        return view('admin.event.index', compact('pageTitle', 'search', 'emptyMessage', 'events'));
    }

    public function create_test_event()
    {
        $pageTitle = __('Test Event');
        $users = User::latest()->where('status', 1)->get();
        return view('admin.event.create_test', compact('pageTitle', 'users'));
    }
    public function store_test_event(Request $request)
    {
        $this->eventService->addTestEvent($request);
        $notify[] = ['success', __('Event added successfully')];
        return back()->withNotify($notify);
    }


    public function duplicate($id){

        try{
            $this->eventService->duplicateEvent($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }
        $notify[] = ['success', 'Event Duplicated successfully'];
        return back()->withNotify($notify);
    }


    public function delete($id){

        try {
            $this->eventService->deleteEvent($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        $notify[] = ['success', 'Event Deleted successfully'];
        return back()->withNotify($notify);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\OfferSheetValidationException;
use App\Exceptions\PushNotificationException;
use App\Http\Controllers\Controller;
use App\Models\OfferSheet;
use App\Models\Size;
use App\Models\User;
use App\Services\OfferSheetService;
use Exception;
use Illuminate\Http\Request;

class OfferSheetController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;

    /**
     * @var OfferSheetService
     */
    protected $offerSheetService;
    public function __construct()
    {
        $this->offerSheetService = app('offerSheetService');
    }
    // protected $search;


    public function index()
    {
        $offerSheets = $this->offerSheetService->latestPaginatedWith(['category']);
        $pageTitle = "All OfferSheets";
        $emptyMessage = "No OfferSheets Found";
        return view('admin.offer_sheet.index', compact('pageTitle', 'emptyMessage', 'offerSheets'));
    }

    public function duplicate($id){

        try{
            $this->offerSheetService->duplicateOfferSheet($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }
        $notify[] = ['success', 'Offer Sheet Duplicated successfully'];
        return back()->withNotify($notify);
    }

    public function create()
    {
        $categories = $this->offerSheetService->getCategories();
        $regions = $this->offerSheetService->getRegions();
        $countries = $this->offerSheetService->getCountries();
        $origins=$this->offerSheetService->getOrigins();
        $pageTitle = 'Create Offer Sheet';
        return view('admin.offer_sheet.create', compact('pageTitle', 'countries', 'regions', 'categories', 'countries','origins'));
    }

    public function get_sizes($id){

        try{

            $sizes=$this->offerSheetService->get_sizes($id);
            return response()->json([
                'sizes'=>$sizes,
                'message'=>'success',
                'status'=>'true',
            ]);

        }catch(Exception $e){

            return response()->json([

                'sizes'=>null,
                'message'=>$e->getMessage(),
                'status'=>'false',
            ]);
        }


    }

    public function edit($id)
    {
        $pageTitle = 'Update Offer Sheet';

        $offerSheet = $this->offerSheetService->getById($id,['fees', 'shippingRegions', 'shippingRegions.shippingRanges','sizes']);
        $categories = $this->offerSheetService->getCategories();
        $regions = $this->offerSheetService->getRegions();
        $countries = $this->offerSheetService->getCountries();
        $origins=$this->offerSheetService->getOrigins();
        //$hasOrigin = $event->origins()->where('origin_id', $origin_id)->exists();
        $shippingregions = $offerSheet->shippingRegions;
        $sizes = $offerSheet->sizes->toArray();

        $fees = $offerSheet->fees;

        return view('admin.offer_sheet.edit', compact('pageTitle', 'countries', 'regions', 'offerSheet', 'fees', 'shippingregions', 'categories','origins'));
    }

    public function store(Request $request)
    {
        try {
            $this->offerSheetService->addOfferSheet($request);
            $notify[] = ['success', 'Offer sheet added successfully'];
            return redirect()->route('admin.event.index', ['type' => 'offerSheets'])->withNotify($notify);
            
        } catch (OfferSheetValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        } catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->offerSheetService->editOfferSheet($request, $id);
        }
        catch (OfferSheetValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
            //will not fail the offer sheet update
        }
        $notify[] = ['success', 'Offer sheet updated successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id){

        try {
            $this->offerSheetService->deleteOfferSheet($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        $notify[] = ['success', 'Offer sheet deleted successfully'];
        return back()->withNotify($notify);
    }




    public function search(Request $request)
    {
        $search = $request->search;
        $offerSheets = $this->offerSheetService->search($search);
        $pageTitle = 'OfferSheet Search - ' . $search;
        $emptyMessage = 'No search result found';
        if ($request->has('user_id')) {
            $id = $request->user_id;
            return view('admin.users.offer_sheets', compact('pageTitle', 'search', 'emptyMessage', 'offerSheets', 'id'));
        }

        return view('admin.offer_sheet.index', compact('pageTitle', 'search', 'emptyMessage', 'offerSheets'));
    }


    public function check_size_delete($id){

        try{
            $size=Size::find($id);
            $message="can be deleted";
            $status="true";
            if($size->prices->count()>0){
                $message="Cannot be deleted";
                $status="false";
            }
            return response()->json([

                'message'=>$message,
                'status'=>$status,
            ]);
        }catch(Exception $e){

            return response()->json([

                'message'=>$e->getMessage(),
                'status'=>'false',
            ]);
        }
    }
}

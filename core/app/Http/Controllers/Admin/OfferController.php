<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\OfferValidationException;
use App\Exceptions\PushNotificationException;
use App\Http\Controllers\Controller;
use App\Jobs\ImportOffersJob;
use App\Jobs\ImportProductsJob;
use App\Models\Bid;
use App\Models\BidHistory;
use App\Models\GeneralSetting;
use App\Models\Media;
use App\Models\Merchant;
use App\Models\OfferSheet;
use App\Models\Price;
use App\Models\Offer;
use App\Models\Winner;
use App\Rules\FileTypeValidate;
use App\Services\OfferService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OfferSpecification;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Sentry\Laravel\Integration;

class OfferController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    protected $search;
    /**
     * @var OfferService
     */
    protected $offerService;

    public function __construct()
    {
        $this->offerService = app('offerService');
    }

    protected $permanentFields = ['Status', 'Units Available', 'Origin', 'Producer', 'Region', 'Altitude', 'Variety', 'Processing Method', 'Drying', 'Grade', 'Screen', 'Harvest', 'Tasting Notes', 'Location', 'Diff / 100 Lb', 'NY KCH24 @ 185'];

    protected function filterOffers($type)
    {

        $offers = Offer::query();
        $this->pageTitle    = ucfirst($type) . ' Offers';
        $this->emptyMessage = 'No ' . $type . ' offers found';

        if ($type != 'all') {
            $offers = $offers->$type();
        }

        if (request()->search) {
            $search  = request()->search;

            $offers    = $offers->where(function ($qq) use ($search) {
                $qq->where('name', 'like', '%' . $search . '%')->orWhere(function ($offer) use ($search) {
                    $offer->whereHas('merchant', function ($merchant) use ($search) {
                        $merchant->where('username', 'like', "%$search%");
                    })->orWhereHas('admin', function ($admin) use ($search) {
                        $admin->where('username', 'like', "%$search%");
                    });
                });
            });

            $this->pageTitle    = "Search Result for '$search'";
            $this->search = $search;
        }

        return $offers->with('merchant', 'admin', 'offerSheet')->latest()->paginate(50);
    }

    public function index()
    {
        $segments       = request()->segments();
        $offers       = $this->filterOffers(end($segments));
        $scope = end($segments);
        $pageTitle      = $this->pageTitle;
        $emptyMessage   = $this->emptyMessage;
        $search         = $this->search;
        $offer_sheets = OfferSheet::latest()->get();
        session()->put('offer_sheet_id', 0);
        return view('admin.offer.index', compact('pageTitle', 'emptyMessage', 'scope', 'offers', 'search', 'offer_sheets'));
    }
    public function filter_by_offer_sheet(Request $request, $scope)
    {

        $offer_sheet = OfferSheet::find($request->offer_sheet_id);
        session()->put('offer_sheet_id', $request->offer_sheet_id);
        $emptyMessage = 'No offers found';
        if ($scope == 'all') {
            if ($request->offer_sheet_id == 0) {
                $pageTitle = 'All Offers';
                $offers = Offer::latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'All Offers';
                $offers = $offer_sheet->offers()->with('merchant', 'admin', 'offerSheet')->latest()->paginate(50);
            }
        } elseif ($scope == 'live') {
            if ($request->offer_sheet_id == 0) {
                $pageTitle = 'Live Offers';
                $offers = Offer::live()->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'Live Offers with Offer Sheet ' . $offer_sheet->name;
                $offers = $offer_sheet->offers()->with('merchant', 'admin', 'offerSheet')->where('status', 1)->where('started_at', '<', now())->where('expired_at', '>', now())->latest()->paginate(50);
            }
        }


        $offer_sheets = OfferSheet::latest()->get();
        return view('admin.offer.index', compact('pageTitle', 'emptyMessage', 'offers', 'offer_sheets', 'scope', 'offer_sheet'));
    }

    public function approve(Request $request)//todo refactor, use the offer service functions
    {
        $request->validate([
            'id' => 'required'
        ]);
        $offer = Offer::findOrFail($request->id);
        $offer->status = 1;
        $offer->save();

        $notify[] = ['success', 'Offer Approved Successfully'];
        return back()->withNotify($notify);
    }

    private  function filterExcludedNames($inputArray, $excludedNames)
    {
        $result = [];

        foreach ($inputArray as $item) {
            $name = $item['name'];

            // Check if the name is not in the excluded names array
            if (!in_array($name, $excludedNames)) {
                $result[] = $item;
            }
        }

        return $result;
    }


    public static function getIndexByName($input, $searchName)
    {
        foreach ($input as $index => $item) {

            if (($item['name'] ?? '') === $searchName) {
                return $index;
            }
        }

        return -1; // Return -1 if the name is not found in the array
    }

    public function create($id = null)
    {
        $pageTitle = 'Add Offer';

        $permanentFields = $this->permanentFields;

        $filterExcludedPremanent = $this->filterExcludedNames(old('specification') ?? [], $permanentFields);

        $merchants = Merchant::active()->orderBy('id', 'desc')->get();
        $offer_sheets = OfferSheet::orderBy('id', 'desc')->get();
        $offer_sheet_id = $id;
        // $colors = $this->getColors();
        $offer_sheet = OfferSheet::find($id);
        $sizes = $id == 0 ? [] : $offer_sheet->sizes;

        return view('admin.offer.create', compact('pageTitle', 'merchants', 'offer_sheets', 'offer_sheet_id', 'sizes', 'filterExcludedPremanent', 'permanentFields'));
    }

    public function edit($id)
    {

        $pageTitle = 'Update Offer';

        $permanentFields =  $this->permanentFields;

        $merchants = Merchant::active()->orderBy('id', 'desc')->get();
        $offer = Offer::findOrFail($id);
        $offer_sheets = OfferSheet::orderBy('id', 'desc')->get();
        // $colors = $this->getColors();
        $sizes = $offer->offerSheet->sizes;

        return view('admin.offer.edit', compact('pageTitle', 'offer_sheets', 'offer', 'merchants', 'sizes', 'permanentFields'));
    }

    public function store(Request $request)//todo refactor, use the offer service functions
    {
        $this->validation($request, 'required');
        $offer = new Offer();
        if (!$request->merchant_id) {
            $offer->admin_id  = auth()->guard('admin')->id();
        } else {
            $offer->admin_id  = null;
        }
        $offer->merchant_id  = $request->merchant_id ? $request->merchant_id : null;
        $offer->status    = 1;

        try {
            $this->saveOffer($request, $offer);
        } catch (OfferValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Offer added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)//todo refactor, use the offer service functions
    {
        $this->validation($request, 'nullable');
        $offer = Offer::findOrFail($id);
        if (!$request->merchant_id) {
            $offer->admin_id  = auth()->guard('admin')->id();
        } else {
            $offer->admin_id  = null;
        }
        $offer->merchant_id  = $request->merchant_id ? $request->merchant_id : null;

        try {
            $this->saveOffer($request, $offer);
        } catch (OfferValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
            $notify[] = ['error', 'Something went wrong'];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', 'Offer updated successfully'];
        clear_all_cache();


        return back()->withNotify($notify);
    }

    public function saveOffer($request, $offer)//todo refactor, use the offer service functions
    {
        $image_name = null;
        if ($request->hasFile('image')) {
            try {
                // foreach($request->images as $image){ todo for store multi images

                //     $imgs[] = uploadImageToS3($image, imagePath()['product']['path'], imagePath()['product']['size'], $offer->image, imagePath()['product']['thumb']);

                // }
                $image_name = uploadImageToS3($request->image, imagePath()['product']['path'],
                    imagePath()['product']['size'],
                    $offer->image,
                    imagePath()['product']['thumb'],
                    true,
                    imagePath()['product']['size_sm'],
                    imagePath()['product']['size_md']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new OfferValidationException('Image could not be uploaded.');
            }
        }

        $offer->name = $request->name;

        // $offer->price = $request->price;
        // $offer->started_at = $request->started_at ?? now();
        // $offer->expired_at = $request->expired_at;
        $offer->short_description = $request->short_description;
        $offer->long_description = $request->long_description;
        //        $offer->specification = $request->specification ?? null;
        $offer->offer_sheet_id = $request->offer_sheet_id;
        // $offer->less_bidding_value=$request->less_bidding_value;
        // $offer->max_auto_bid_price=$request->max_auto_bid_price;
        // $offer->max_auto_bid_steps=$request->max_auto_bid_steps;
        // $offer->color_class=$request->color_class;
        $offer->save();
        if (isset($image_name)) {
            $offer->files()->delete();
            $this->storeImage($image_name, $offer, 'image', null);
        }

        if ($request->specification) {
            $specification = OfferSpecification::where('offer_id', $offer->id)->get();
            if ($specification) {
                OfferSpecification::where('offer_id', $offer->id)->delete();
            }
            foreach ($request->specification as $key => $spec) {
                $specification = new OfferSpecification;
                $specification->offer_id = $offer->id;
                $specification->spec_key = $spec['name'];
                $specification->Value = $spec['value'];
                // $specification->is_display = $spec['is_display'];
                $specification->save();
            }
        }

        if ($request->sizes) {
            // $sizes=Price::where('offer_id',$offer->id)->get();
            // if($sizes){
            //     Price::where('offer_id',$offer->id)->delete();
            // }

            $priceIds = collect($request->sizes)->pluck('unit')->filter();
            foreach ($offer->prices()->whereNotIn('size_id', $priceIds)->get() as $price) {
                if ($price->orders()->count() == 0)
                    $price->delete();
            }
            foreach ($request->sizes as $key => $size) {
                $price = Price::where('offer_id', $offer->id)->where('size_id', $size['unit'])->first();
                if ($price) {
                    $price->update([
                        'price' => $size['price']
                    ]);
                } else {
                    $newprice = new Price;
                    $newprice->offer_id = $offer->id;
                    $newprice->size_id = $size['unit'];
                    $newprice->price = $size['price'];
                    $newprice->save();
                }
            }
        }

        // foreach($offer->autobidsettings as $setting){
        //     if($setting->step < $offer->less_bidding_value){
        //         $setting->update([
        //             'step'=>$offer->less_bidding_value,
        //         ]);
        //     }
        // }
        clear_all_cache();
    }

    public function storeImage($file_name, $object, $type = 'image', $zone = null) //todo refactor, use the offer service functions
    {
        Media::create([
            'file_name' => $file_name,
            'model_type' => get_class($object),
            'model_id' => $object->id,
            'file_type' => $type,
            'zone' => $zone,
        ]);
    }

    protected function validation($request, $imgValidation)//todo refactor, use the offer service functions
    {

        $request->validate([
            'name'                  => 'required',

            // 'price'                 => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            // 'expired_at'            => 'required',
            'merchant_id'           => 'required',
            // 'short_description'     => 'required',
            // 'long_description'      => 'required',
            'specification'         => 'nullable|array',
            'specification.*.value'         => 'required|nullable',
            'specification.*.name'         => 'required|nullable',
            // 'started_at'            => 'required_if:schedule,1|date|after:yesterday|before:expired_at',
            'image'                 => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'offer_sheet_id'              => 'required',
            // 'less_bidding_value'        => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            // 'max_auto_bid_price' => 'nullable',
            // 'max_auto_bid_steps' => 'nullable',
            // 'color_class' => 'nullable',
            'sizes' => 'required|array',
            'sizes.*.unit' => 'required',
            'sizes.*.price' => ['required', 'numeric',
//                function ($attribute, $value, $fail) {
//                if ($value <= 0) {
//                    $fail('The Price field must be more than 0.');
//                }
//            }
            ],
        ]);
    }


    public function offerBids($id)
    {
        $offer_id = $id;
        $offer = Offer::with('winner')->findOrFail($id);
        $pageTitle = $offer->name . ' Bids';
        $emptyMessage = $offer->name . ' has no bid yet';
        $bids = Bid::where('offer_id', $id)->with('user', 'offer', 'winner')->withCount('winner')->orderBy('id', 'DESC')->latest()->paginate(getPaginate());
        return view('admin.offer.offer_bids', compact('pageTitle', 'emptyMessage', 'bids', 'offer_id'));
    }

    public function bidWinner(Request $request)
    {
        $request->validate([
            'bid_id' => 'required'
        ]);

        $bid = Bid::with('user', 'offer')->findOrFail($request->bid_id);
        $offer = $bid->offer;
        $winner = Winner::where('offer_id', $offer->id)->exists();

        if ($winner) {
            $notify[] = ['error', 'Winner for this offer is already selected'];
            return back()->withNotify($notify);
        }

        if ($offer->expired_at > now()) {
            $notify[] = ['error', 'This offer is not expired till now'];
            return back()->withNotify($notify);
        }

        $user = $bid->user;
        $general = GeneralSetting::first();

        $winner = new Winner();
        $winner->user_id = $user->id;
        $winner->offer_id = $offer->id;
        $winner->bid_id = $bid->id;
        $winner->save();

        notify($user, 'BID_WINNER', [
            'offer' => $offer->name,
            'offer_price' => showAmount($offer->price),
            'currency' => $general->cur_text,
            'amount' => showAmount($bid->amount),
        ]);

        $notify[] = ['success', 'Winner selected successfully'];
        return back()->withNotify($notify);
    }

    public function offerWinner()
    {
        $pageTitle = 'All Winners';
        $emptyMessage = 'No winner found';
        $winners = Winner::with('offer', 'user')->latest()->paginate(getPaginate());

        return view('admin.offer.winners', compact('pageTitle', 'emptyMessage', 'winners'));
    }

    public function deliveredOffer(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('offer')->whereHas('offer')->findOrFail($request->id);
        $winner->offer_delivered = 1;
        $winner->save();

        $notify[] = ['success', 'Offer mark as delivered'];
        return back()->withNotify($notify);
    }

    public function undoBid($id)
    {
        clear_all_cache();
        $bid = Bid::where('offer_id', $id)->orderby('amount', 'desc')->first();
        $offer = Offer::findOrFail($id);

        $notify = [];
        if ($bid) {
            $history_bid = BidHistory::where('offer_id', $id)->where('user_id', $bid->user_id)->orderby('updated_at', 'desc')->first();
            if ($history_bid && $history_bid->user_previous_bid != 0) {
                $prev_amount_bid = $history_bid->user_previous_bid;
                $history_bid->delete();
                $bid->prev_amount = -1;
                $bid->amount = $prev_amount_bid;
                $bid->save();
                $offer->total_bid = $offer->total_bid - 1;
                $offer->save();
                $notify[] = ['success', 'Successfully undo the last bid'];
            } else {
                $deleted = false;
                if ($history_bid) {
                    $history_bid->delete();
                    $deleted = true;
                }
                if ($bid) {
                    $bid->delete();
                    $deleted = true;
                }

                if ($deleted) {
                    $offer->total_bid = $offer->total_bid - 1;
                    $offer->save();
                    $notify[] = ['success', 'Successfully undo the last bid'];
                }
            }
        }

        if (!$notify) {
            $notify[] = ['error', 'The last bid cannot be undone'];
        }

        try {
            clear_all_cache();
        } catch (\Exception $e) {
            $notify[] = ['error', 'Error in sending push notification'];
        }
        return back()->withNotify($notify);
    }


    private function getColors()
    {
        //bg-ace-brown
        //bg-coe-green
        //bg-coe-black
        //bg-mc-blue
        //bg-mc-red1
        //bg-mc-red2
        //bg-mc-green
        //bg-mc-yellow
        //bg-mc-purple
        //bg-mc-indigo
        return [
            'bg-ace-brown' => 'Ace Brown',
            'bg-coe-green' => 'Coe Green',
            'bg-coe-black' => 'Coe Black',
            'bg-mc-blue' => 'Mc Blue',
            'bg-mc-red1' => 'Mc Red 1',
            'bg-mc-red2' => 'Mc Red 2',
            'bg-mc-green' => 'Mc Green',
            'bg-mc-yellow' => 'Mc Yellow',
            'bg-mc-purple' => 'Mc Purple',
            'bg-mc-indigo' => 'Mc Indigo',
        ];
    }

    public function check_price_delete($id)
    {
        try {
            $price = Price::find($id);
            $message = "can be deleted";
            $status = "true";
            if ($price->orders()->count() > 0) {
                $message = "Cannot be deleted";
                $status = "false";
            }
            return response()->json([

                'message' => $message,
                'status' => $status,
            ]);
        } catch (Exception $e) {

            return response()->json([

                'message' => $e->getMessage(),
                'status' => 'false',
            ]);
        }
    }

    public function duplicate($id)
    {

        try {
            $offer = Offer::find($id);
            $duplicated_offer = $offer->replicate();
            $duplicated_offer->save();

            foreach ($offer->prices as $price) {
                $duplicated_offer->prices()->create($price->toArray());
            }

            foreach ($offer->offer_specification as $spec) {
                $duplicated_offer->offer_specification()->create($spec->toArray());
            }

            foreach ($offer->files as $file) {
                $duplicated_offer->files()->create($file->toArray());
            }
        } catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        $notify[] = ['success', 'Offer Duplicated successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {

        try {
            $offer = Offer::find($id);

            if ($offer->prices()->count()) {
                $offer->prices()->delete();
            }
            if ($offer->offer_specification()->count()) {
                $offer->offer_specification()->delete();
            }
            if ($offer->files()->count()) {
                $offer->files()->delete();
            }
            $offer->delete();
        } catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        $notify[] = ['success', 'Offer deleted successfully'];
        return back()->withNotify($notify);
    }
    public function import($uuid = null)
    {
        $pageTitle = __('Import Offers');

        return view('admin.offer.import', compact('pageTitle','uuid'));//todo get the uuid from here to display the log file upon completion
    }
    public function importOfferFromCsv(Request $request)
    {
        // ================================================
        // 1. check if file exist
        // 2. check if file is csv
        // 4. import offer from csv
//        ================================================
        // Example: Save the uploaded file
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $uuid = $request->input('uuid');
            if (is_null($uuid)) {
                return  back()->withNotify([['error',__('uuid is required')]]);
            }

            $original_fileName=$file->getClientOriginalName();
            $fileName = $uuid.'.'. $file->getClientOriginalExtension(); // You can generate a unique name
            if($file->extension() != 'csv' )
                return  back()->withNotify([['error',__('file must be csv')]]);
            $content = file_get_contents($file);
            $encoding=mb_detect_encoding($content, "UTF-8, ISO-8859-1, ISO-8859-15", true);
            if ($encoding!='UTF-8') {
                return  back()->withNotify([['error',__('file encoding must be UTF-8')]]);
            }

            $path = 'importFiles/offers';
            $saveFile=  Storage::putFileAs($path , $file,  $fileName);
            $pathCsv = Storage::path($saveFile);


            if (Storage::exists($saveFile)) {
//                ImportProductHelper::importProductFromCsv($pathCsv,$tenantId ,$uuid,$file);
                $commandName = 'ImportOffersCommand';
                // Define the parameters as an array
                $parameters = [
                    '--pathCsv' => $pathCsv,
                    '--uuid' => $uuid,
                    '--originalFileName' => $original_fileName,
                ];

                // Run the command
                $job = new ImportOffersJob($commandName, $parameters);
                dispatch($job)->onConnection('sync');//wait todo make async and send notification
                return redirect()->route('admin.offer.get_log_file',['uuid'=>$uuid,'option'=>'down']);
//                return redirect()->route('admin.offers.import',['uuid'=>$uuid]);todo

            }else{
                return  back()->withNotify([['error',__('file not saved')]]);
            }

        }else{
            return  back()->withNotify([['error',__('file not found')]]);
        }
    }
    public function get_log_file($uuid,$option=null){//todo call this function with option:null to get content and display it every 5 seconds in front end.

        try {
            $result = $this->offerService->get_log_file($uuid, $option);
            if ($option == 'down')
                //download
                return redirect()->secure($result);
            return response()->json([
                'data' => $result,
                'status' => true,
                'message' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'status' => false,
                'message' => 'faild'
            ]);
        }
    }

    public function download_template(){

        $filename='offer_template.csv';
        $filePath = public_path('/assets/templates/csv/'.$filename);
        if (!File::exists($filePath)) {
            abort(404);
        }
        return Response::download($filePath, $filename);

    }
}

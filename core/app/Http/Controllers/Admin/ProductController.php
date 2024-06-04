<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ProductValidationException;
use App\Exceptions\PushNotificationException;
use App\Http\Controllers\Controller;
use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Jobs\ImportProductsJob;
use App\Models\Bid;
use App\Models\BidHistory;
use App\Models\Category;
use App\Models\Event;
use App\Models\GeneralSetting;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Winner;
use App\Rules\FileTypeValidate;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    protected $search;
    /**
     * @var ProductService
     */
    protected $productService;
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->productService = app('productService');
    }


    protected function filterProducts($type){

        $products = Product::query();
        $this->pageTitle    = ucfirst($type). ' Products';
        $this->emptyMessage = 'No '.$type. ' products found';

        if($type != 'all'){
            $products = $products->$type();
        }

        if(request()->search){
            $search  = request()->search;

            $products    = $products->where(function($qq) use ($search){
                $qq->where('name', 'like', '%'.$search.'%')->orWhere(function($product) use($search){
                    $product->whereHas('merchant', function ($merchant) use ($search) {
                        $merchant->where('username', 'like',"%$search%");
                    })->orWhereHas('admin', function ($admin) use ($search) {
                        $admin->where('username', 'like',"%$search%");
                    });
                });
            });

            $this->pageTitle    = "Search Result for '$search'";
            $this->search = $search;
        }

        return $products->with('merchant', 'admin','event')->latest()->paginate(50);
    }

    public function index()
    {
        $segments       = request()->segments();
        $products       = $this->filterProducts(end($segments));
        $scope=end($segments);
        $pageTitle      = $this->pageTitle;
        $emptyMessage   = $this->emptyMessage;
        $search         = $this->search;
        $events=Event::latest()->get();
        session()->put('event_id',0);
        return view('admin.product.index', compact('pageTitle', 'emptyMessage','scope', 'products', 'search','events'));
    }
    public function filter_by_event(Request $request,$scope){

        $event=Event::find($request->event_id);
        session()->put('event_id',$request->event_id);
        $emptyMessage = __('No products found');
            if($scope=='all'){
                if ($request->event_id == 0) {
                    $pageTitle = __('All Products');
                    $products = Product::latest()->paginate(getPaginate());
                }else{
                    $pageTitle = 'All Products with Event '.$event->name;
                    $products=$event->products()->with('merchant', 'admin','event')->latest()->paginate(50);
                }

            }elseif($scope=='live'){
                if ($request->event_id == 0) {
                    $pageTitle = __('Live Products');
                    $products = Product::live()->latest()->paginate(getPaginate());
                }else{
                    $pageTitle = 'Live Products with Event '.$event->name;
                    $products=$event->products()->with('merchant', 'admin','event')->where('status', 1)->where('started_at', '<', now())->where('expired_at', '>', now())->latest()->paginate(50);
                }
            }elseif($scope=='pending'){
                if ($request->event_id == 0) {
                    $pageTitle = __('Pending Products');
                    $products = Product::pending()->latest()->paginate(getPaginate());
                }else{
                    $pageTitle = 'Pending Products with Event '.$event->name;
                    $products=$event->products()->with('merchant', 'admin','event')->where('status', 0)->where('expired_at', '>', now())->latest()->paginate(50);
                }
            }elseif($scope=='upcoming'){
                if ($request->event_id == 0) {
                    $pageTitle = __('Upcoming Products');
                    $products = Product::upcoming()->latest()->paginate(getPaginate());
                }else{
                    $pageTitle = 'Upcoming Products with Event '.$event->name;
                    $products=$event->products()->with('merchant', 'admin','event')->where('status', 1)->where('started_at', '>', now())->latest()->paginate(50);
                }
            }elseif($scope=='expired'){
                if ($request->event_id == 0) {
                    $pageTitle = __('Expired Products');
                    $products = Product::expired()->latest()->paginate(getPaginate());
                }else{
                    $pageTitle = 'Expired Products with Event '.$event->name;
                    $products=$event->products()->with('merchant', 'admin','event')->where('expired_at', '<', now())->latest()->paginate(50);
                }
            }


        $events=Event::latest()->get();
        return view('admin.product.index', compact('pageTitle', 'emptyMessage', 'products','events','scope'));
    }

    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $product = Product::findOrFail($request->id);
        $product->status = 1;
        $product->save();

        $notify[] = ['success', __('Product Approved Successfully')];
        return back()->withNotify($notify);
    }

    public function create($id=null)
    {
        $pageTitle = __('Create Product');

        $merchants = Merchant::active()->orderBy('id','desc')->get();
        $events = Event::orderBy('id','desc')->get();
        $event_id=$id;
        $colors = $this->getColors();
        return view('admin.product.create', compact('pageTitle' ,'merchants','events','event_id','colors'));
    }

    public function edit($id)
    {
        $pageTitle = __('Update Product');

        $merchants = Merchant::active()->orderBy('id','desc')->get();
        $product = Product::findOrFail($id);
        $events = Event::orderBy('id','desc')->get();
        $colors = $this->getColors();

        return view('admin.product.edit', compact('pageTitle','events', 'product' , 'merchants','colors'));
    }

    public function store(Request $request)
    {
        $validated=$this->productService->validation($request, 'required');
        try {
            $image = $this->productService->saveImage($request,'image');
            $product=$this->productService->createProduct($validated,$image);
        }catch (ProductValidationException $e){
            $notify[] = ['error',$e->getMessage()];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', __('Product added successfully')];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $validated=$this->productService->validation($request, 'nullable');
        $product = Product::findOrFail($id);
        try {
            $image = $this->productService->saveImage($request,'image');
            $this->productService->saveProduct($validated, $product,$image);
            //todo delete old image here
        }catch (ProductValidationException $e){
            $notify[] = ['error',$e->getMessage()];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', __('Product updated successfully')];
        try {
            clear_all_cache();

            event(new ProductPush($product->id));
        } catch (\Exception $e) {
            $notify[] = ['error', __('Error in sending push notification')];
        }

        return back()->withNotify($notify);
    }




    public function productBids($id)
    {
        $product_id = $id;
        $product = Product::with('winner')->findOrFail($id);
        $pageTitle = $product->name.' Bids';
        $emptyMessage = $product->name.' has no bid yet';
        $bids = Bid::where('product_id', $id)->with('user', 'product', 'winner')->withCount('winner')->orderBy('id', 'DESC')->latest()->paginate(getPaginate());
        return view('admin.product.product_bids', compact('pageTitle', 'emptyMessage', 'bids', 'product_id'));
    }

    public function bidWinner(Request $request)
    {
        $request->validate([
            'bid_id' => 'required'
        ]);

        $bid = Bid::with('user', 'product')->findOrFail($request->bid_id);
        $product = $bid->product;
        $winner = Winner::where('product_id', $product->id)->exists();

        if($winner){
            $notify[] = ['error', __('Winner for this product is already selected')];
            return back()->withNotify($notify);
        }

        if($product->expired_at > now()){
            $notify[] = ['error', __('This product is not expired till now')];
            return back()->withNotify($notify);
        }

        $user = $bid->user;
        $general = GeneralSetting::first();

        $winner = new Winner();
        $winner->user_id = $user->id;
        $winner->product_id = $product->id;
        $winner->bid_id = $bid->id;
        $winner->save();

        notify($user, 'BID_WINNER', [
            'product' => $product->name,
            'product_price' => showAmount($product->price),
            'currency' => $general->cur_text,
            'amount' => showAmount($bid->amount),
        ]);

        $notify[] = ['success', __('Winner selected successfully')];
        return back()->withNotify($notify);
    }

    public function productWinner(){
        $pageTitle = __('All Winners');
        $emptyMessage = __('No winner found');
        $events=Event::where('start_status','ended')->latest()->get();
        session()->put('event_id',0);
        $winners = Winner::with('product', 'user')->latest()->paginate(getPaginate());

        return view('admin.product.winners', compact('pageTitle', 'emptyMessage', 'winners','events'));
    }

    public function filter_winners_by_event(Request $request){
        $emptyMessage = __('No winner found');
        $events=Event::where('start_status','ended')->latest()->get();
        if($request->event_id==0){
            $pageTitle = __('All Winners');
            session()->put('event_id',0);
            $winners = Winner::with('product', 'user')->latest()->paginate(getPaginate());
        }else{
            $event=Event::find($request->event_id);
            $pageTitle = $event->name.' '.$event->sname.' Winners';
            $products_ids=$event->products->pluck(['id']);
            $winners = Winner::with('product', 'user')->whereIn('product_id',$products_ids)->latest()->paginate(getPaginate());
            session()->put('event_id',$request->event_id);
        }
        return view('admin.product.winners', compact('pageTitle', 'emptyMessage', 'winners','events'));

    }

    public function edit_winner_caption(Request $request){
        $winner=Winner::find($request->id);
        $winner->update([
            'caption'=>$request->caption
        ]);

        return response()->json([
            'caption'=>$winner->caption,
        ]);
    }

    public function deliveredProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 1;
        $winner->save();

        $notify[] = ['success', __('Product mark as delivered')];
        return back()->withNotify($notify);

    }

    public function undoBid($id)
    {
        clear_all_cache();
        $bid = Bid::where('product_id', $id)->orderby('amount', 'desc')->first();
        $product = Product::findOrFail($id);

        $notify = [];
        if($bid){
            $history_bid = BidHistory::where('product_id', $id)->where('user_id', $bid->user_id)->orderby('updated_at', 'desc')->first();
            if($history_bid && $history_bid->user_previous_bid != 0 ){
                $prev_amount_bid = $history_bid->user_previous_bid;
                $history_bid->delete();
                $bid->prev_amount = -1;
                $bid->amount = $prev_amount_bid;
                $bid->save();
                $product->total_bid = $product->total_bid - 1;
                $product->save();
                $notify[] = ['success', __('Successfully undo the last bid')];

            }else{
                $deleted = false;
                if($history_bid){
                    $history_bid->delete();
                    $deleted = true;
                }
                if($bid){
                    $bid->delete();
                    $deleted = true;
                }

                if ($deleted){
                    $product->total_bid = $product->total_bid - 1;
                    $product->save();
                    $notify[] = ['success', __('Successfully undo the last bid')];
                }

            }
        }

        if(!$notify){
            $notify[] = ['error', __('The last bid cannot be undone')];
        }

        try {
            clear_all_cache();

            event(new ProductPush($product->id));
            event(new EventPush($product->event_id));

        } catch (\Exception $e) {
            $notify[] = ['error', __('Error in sending push notification')];
        }
        return back()->withNotify($notify);
    }


    private function getColors(){
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
    public function import($uuid = null)
    {
        $pageTitle = __('Import Products');

        return view('admin.product.import', compact('pageTitle','uuid'));//todo get the uuid from here to display the log file upon completion
    }
    public function importProductFromCsv(Request $request)
    {
        // ================================================
        // 1. check if file exist
        // 2. check if file is csv
        // 4. import product from csv
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

            $path = 'importFiles/products';
            $saveFile=  Storage::putFileAs($path , $file,  $fileName);
            $pathCsv = Storage::path($saveFile);


            if (Storage::exists($saveFile)) {
//                ImportProductHelper::importProductFromCsv($pathCsv,$tenantId ,$uuid,$file);
                $commandName = 'ImportProductsCommand';
                // Define the parameters as an array
                $parameters = [
                    '--pathCsv' => $pathCsv,
                    '--uuid' => $uuid,
                    '--originalFileName' => $original_fileName,
                ];

                // Run the command
                $job = new ImportProductsJob($commandName, $parameters);
                dispatch($job)->onConnection('sync');//wait todo make async and send notification
                return redirect()->route('admin.product.get_log_file',['uuid'=>$uuid,'option'=>'down']);
//                return redirect()->route('admin.products.import',['uuid'=>$uuid]);todo

            }else{
                return  back()->withNotify([['error',__('file not saved')]]);
            }

        }else{
            return  back()->withNotify([['error',__('file not found')]]);
        }
    }
    public function get_log_file($uuid,$option=null){//todo call this function with option:null to get content and display it every 5 seconds in front end.

        try {
            $result = $this->productService->get_log_file($uuid, $option);
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

        $filename='product_template.csv';
        $filePath = public_path('/assets/templates/csv/'.$filename);
        if (!File::exists($filePath)) {
            abort(404);
        }
        return Response::download($filePath, $filename);

    }

    public function duplicate($id){

        try{
            $this->productService->duplicateProduct($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }
        $notify[] = ['success', 'Product Duplicated successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id){

        try {
            $this->productService->deleteProduct($id);
        }
        catch (PushNotificationException $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        $notify[] = ['success', 'Product Deleted successfully'];
        return back()->withNotify($notify);
    }
    
}

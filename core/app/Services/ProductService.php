<?php

namespace App\Services;


use App\Exceptions\BidValidationException;
use App\Exceptions\ProductValidationException;
use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Jobs\AutoBidForProductId;
use App\Models\AdminNotification;
use App\Models\AutoBidSetting;
use App\Models\Bid;
use App\Models\Event;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\Review;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Aws\S3\S3Client;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Sentry\Laravel\Integration;
use Illuminate\Support\Facades\File;

class ProductService extends BaseService{
    /**
     * @param $search_key
     * @param $category_id
     * @return array
     */
    public function getProducts($search_key, $category_id)
    {
        $products = Product::live();
        $products = $products->where('name', 'like', '%' . $search_key . '%');


        if ($category_id) {
            $products = $products->where('category_id', $category_id);
        }
        $minPrice = $products->clone()->min('price');
        $maxPrice = $products->clone()->max('price');
        $products = $products->paginate(getPaginate(18));
        return [$products, $minPrice, $maxPrice];
    }
    /**
     * @param array $validated
     * @return mixed
     */
    public function getProductsFiltered( $validated)
    {
        $products = Product::live()->where('name', 'like', '%' . $validated['search_key'] . '%');

        if ($validated['sorting']) {
            $products->orderBy($validated['sorting'], 'ASC');
        }
        if ($validated['categories']) {
            $products->whereIn('category_id', $validated['categories']);
        }
        if ($validated['minPrice']) {
            $products->where('price', '>=', $validated['minPrice']);
        }
        if ($validated['maxPrice']) {
            $products->where('price', '<=', $validated['maxPrice']);
        }
        if ($validated['country'] && $validated['country'] != "All") {
            $products->where('specification->5->value', 'like', '%' . $validated['country']);
        }
        if ($validated['score'] && $validated['score'] != "All") {
            if ($validated['score'] == "80-83") {
                $down_s = 80;
                $up_s = 83;
            } elseif ($validated['score'] == "84-86") {
                $down_s = 84;
                $up_s = 86;
            } else {
                $down_s = 86;
                $up_s = 10000;
            }

            $products->where('specification->2->value', '>=', $down_s)->where('specification->2->value', '<=', $up_s);
        }
        if ($validated['location'] && $validated['location'] != "All") {
            $products->where('specification->6->value', 'like', '%' . $validated['location']);
        }
        $products = $products->paginate(getPaginate(18));
        return $products;
    }

    public function loadReviews($pid)
    {
        return Review::where('product_id', $pid)->with('user')->latest()->paginate(5);

    }

    public function productDetails($id,$user_id=null)
    {
        if ($user_id) {
            $productid = get_allowed_products($user_id);

//            if (in_array($id, $productid)) { todo check why this condition is commented out
            $product = Product::with('reviews', 'merchant', 'reviews.user')->where('status', '!=', 0)->findOrFail($id);
            $relatedProducts = Product::live()->where('id', '!=', $id)->whereIn('id', $productid)->where('event_id',$product->event_id)->limit(10)->get();
//            } else {
            //return redirect('/events');
//            }
        } else {
            $product = Product::with('reviews', 'merchant', 'reviews.user')->where('status', '!=', 0)->findOrFail($id);
            $relatedProducts = Product::live()->where('id', '!=', $id)->where('event_id',$product->event_id)->limit(10)->get();
        }


        $event = Event::find($product->event_id);
        $imageData = imagePath()['product'];

        $seoContents = getSeoContents($product, $imageData, 'image');
        $max_bid =
            $product->max_bid();

        $amount = ($max_bid) ? $max_bid->amount : $product->price;
        $new_bidding_value = floatval($amount) + floatval($product->less_bidding_value);
        $new_bidding_value = round($new_bidding_value, 2);
        $amount = showAmount($amount);
        $autosettings=null;
        if($user_id){
            $autosettings=$product->autobidsettings->where('user_id',auth()->user()->id)->first();
        }

        return [
            $product,
            $relatedProducts,
            $seoContents,
            $event,
            $new_bidding_value,
            $amount,
            $autosettings
        ];
    }
    /**
     * @param $merchant_id
     * @param $rating
     * @param  $description
     * @return bool
     */
    public function createOrUpdateMerchantReview($merchant_id, $rating, $description)
    {
        $merchant = Merchant::with('bids')->whereHas('bids', function ($bid) {
            $bid->where('user_id', auth()->id());
        })
            ->findOrFail($merchant_id);


        $review = Review::where('user_id', auth()->id())->where('merchant_id', $merchant_id)->first();

        if (!$review) {
            $review = new Review();
            $merchant->total_rating += $rating;
            $merchant->review_count += 1;
            $updated = false;
        } else {
            $merchant->total_rating = $merchant->total_rating - $review->rating + $rating;
            $updated = true;
        }

        $merchant->avg_rating = $merchant->total_rating / $merchant->review_count;
        $merchant->save();

        $review->rating = $rating;
        $review->description = $description;
        $review->user_id = auth()->id();
        $review->merchant_id = $merchant_id;
        $review->save();
        return $updated;
    }
    /**
     * @param $product_id
     * @param $rating
     * @param $description
     * @return bool
     */
    public function createOrUpdateProductReview($product_id, $rating, $description)
    {
        Bid::where('user_id', auth()->id())->where('product_id', $product_id)->firstOrFail();


        $review = Review::where('user_id', auth()->id())->where('product_id', $product_id)->first();
        $product = Product::find($product_id);

        if (!$review) {
            $review = new Review();
            $product->total_rating += $rating;
            $product->review_count += 1;
            $updated = false;
        } else {
            $product->total_rating = $product->total_rating - $review->rating + $rating;
            $updated = true;
        }

        $product->avg_rating = $product->total_rating / $product->review_count;
        $product->save();

        $review->rating = $rating;
        $review->description = $description;
        $review->user_id = auth()->id();
        $review->product_id = $product_id;
        $review->save();
        return $updated;
    }
    public function importProductFromCsv($fileCSV, $uuid, $original_fileName)
    {
        if(config('filesystems.default') == 's3'){
            $client = $this->getS3Client();
            $client->registerStreamWrapper();
            $s3FilePath = 's3://' . config('filesystems.disks.s3.bucket') . '/' . $fileCSV;
            $CSV=file_get_contents($s3FilePath);
            Storage::disk('local')->put('/ImportFiles/products/'.basename($s3FilePath) ,$CSV);
            $fileCSV=Storage::disk('local')->path('/ImportFiles/products/'.basename($s3FilePath));
        }
        $orginal_file = new UploadedFile(
            $fileCSV,
            basename($fileCSV), // The original file name
            mime_content_type($fileCSV), // The file's MIME type
            filesize($fileCSV), // The file's size in bytes
            true, // error status (can be null)
            true // test mode, set to true to prevent the file from being moved
        );
        $default_image_file = new UploadedFile(
            public_path('assets/images/product_import_default_image.png'),
            'product_import_default_image.png', // The original file name
            mime_content_type(public_path('assets/images/product_import_default_image.png')), // The file's MIME type
            filesize(public_path('assets/images/product_import_default_image.png')), // The file's size in bytes
            true, // error status (can be null)
            true // test mode, set to true to prevent the file from being moved
        );

        $counter = 0; // counter for products added
        $rowNumber = 1; // row number in csv file
        $log = "";  // log file
        $log .= "Start DateTime: ".Carbon::now()."\n";
        $log .= "Csv File Name: ".$original_fileName."\n";
        $log .= "Csv File Size: ".$orginal_file->getSize()/ (1024 * 1024)." Mega Bytes\n";
        $log .= "................................\n";
        $counterFailed = 0; // counter for products failed

        $path ="ImportFiles/products/".$uuid.".txt";

        Storage::put($path, $log);


        // read csv file
        $products = (new FastExcel)->configureCsv(',', '"', 'UTF-8')
            ->import($fileCSV, function ($line) use
            ($default_image_file, &$counter, &$log, &$counterFailed, &$rowNumber , $path) {


                $rowNumber++; // increment row number

                // trim all data in row and replace default values with nulls
                $line = array_map(function ($value) {
                    $trim = trim($value);
                    return $trim == '' ? null : $trim;
                }, $line);
                //extract spread specs data to specification field in input
                $line['specification']=$this->extractSpecificationFields($line);
                //validate data in row
                $validator = Validator::make($line, $this->getValidationRules('nullable'));

                // if validation fails
                if ($validator->fails()) {
                    // Get all validation errors
                    $fields = $validator->messages()->get('*');
                    try {
                        // Log the errors
                        $log = "row #$rowNumber :Validation errors for product: " . ($line['name']??'Undefined') . ":\n";
                        foreach ($fields as $field => $errors) {
                            foreach ($errors as $error)
                                $log .= " - " . $error . "\n";
                        }
                        $log .= "\n";
                        Storage::append($path, $log); // save log file
                    }catch (\Exception $e) {
                        // Log the errors
                        $log = " \n";
                        $log .= " Invalid file or template \n  \n";
                        Storage::append($path, $log); // save log file
                    }
                    $counterFailed++; // increment counter for products failed
                } else {
                    try {
                        $this->createProduct($line,$this->saveImageAndReturnName($default_image_file));
                        $counter++;// increment counter for accounts added
                    } catch (\Exception $e) {
                        // Log the errors
                        $log = "row #$rowNumber : unexpected  error for product: " . ($line['name']??'Undefined') . ":\n";
                        $log .= " - " . $e->getMessage() . "\n";
                        Storage::append($path, $log); // save log file
                        $counterFailed++; // increment counter for products failed
                    }
                }

            });// end read csv file
        // add log
        $log = "................................\n";
        $log .= "End DateTime: " . Carbon::now()."\n";
        $log .= "Total rows: " . ($rowNumber - 1) . "\n";
        $log .= "Total products added: " . $counter . "\n";
        $log .= "Total products failed: " . $counterFailed . "\n";
        Storage::append($path, $log);

        // delete csv file from local storage if it is s3
        if(config('filesystems.default') == 's3'){
            File::delete($fileCSV);
        }

    }
    public function saveImage($request,$fieldName)
    {
        if ($request->hasFile($fieldName)) {
            try {
                $img = $request->$fieldName;
                return $this->saveImageAndReturnName($img);
            } catch (\Exception $exp) {
                Log::error($exp->getMessage());
                Integration::captureUnhandledException($exp);
                throw new ProductValidationException('Image could not be uploaded.');
            }
        }
        return null;
    }
    public function createProduct($validated,$image)
    {
        $product = new Product();
        $product->status = 1;
        return $this->saveProduct($validated, $product,$image);

    }
    public function saveProduct($data, $product,$image)
    {
        if($data['merchant_id']==0){
            $product->merchant_id=0;
            $product->admin_id  = auth()->guard('admin')->id();
        }else{
            $product->admin_id  = 0;
            $product->merchant_id  = $data['merchant_id'];
        }
        if($image) {
            $product->image = $image;
        }
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->started_at = $data['started_at'] ?? now();
        $product->expired_at = $data['expired_at'];
        $product->short_description = $data['short_description'];
        $product->long_description = $data['long_description'];
        $product->specification = $data['specification'] ?? null;
        $product->event_id = $data['event_id'];
        $product->less_bidding_value = $data['less_bidding_value'];
        $product->max_auto_bid_price = $data['max_auto_bid_price'];
        $product->max_auto_bid_steps = $data['max_auto_bid_steps'];
        $product->color_class = $data['color_class'];
        $product->save();

        if ($data['specification']) {//todo sync specifications instead of this method
            $specifications = ProductSpecification::where('product_id', $product->id)->get();
            if ($specifications->isNotEmpty()) {
                ProductSpecification::where('product_id', $product->id)->delete();
            }
            foreach ($data['specification'] as $key => $spec) {
                $specification = new ProductSpecification;
                $specification->product_id = $product->id;
                $specification->spec_key = $spec['name'];
                $specification->Value = $spec['value'];
                $specification->is_display = $spec['is_display'];
                $specification->save();
            }
        }


        foreach($product->autobidsettings as $setting){
            if($setting->step < $product->less_bidding_value){
                $setting->update([
                    'step'=>$product->less_bidding_value,
                ]);
            }
        }
        clear_all_cache();
        return $product;
    }

    private function extractSpecificationFields(array $line)
    {
        $specificationList = ['Boxes', 'Score', 'Variety', 'Weight', 'Process', 'Region', 'Rank',
            'Village', 'Altitude', 'Drying Days', 'Tasting Notes', 'Process Information',
            'Cupping Notes', 'Process Description',
            'Washing Station', 'Screen Size', 'Date of Harvest'];
        $specificationsKeyValueArray=[];
        foreach ($specificationList as $specification) {
            if (!empty($line[$specification])) {
                $specificationsKeyValueArray[] = ['name'=>$specification,'value'=>$line[$specification],'is_display'=>1];
            }
        }

        return $specificationsKeyValueArray;
    }
    private function getValidationRules($imgValidation)
    {
        return [
            'name' => 'required',

            'price' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'expired_at' => 'required|date|after:started_at',
            'merchant_id' => 'required',
            'short_description' => 'nullable',
            'long_description' => 'nullable',
            'specification' => ['required','array',function ($attribute, $value, $fail) {
                $weight = collect($value)->where('name','Weight')->first()['value']??null;
//                Log::info($weight);
                if (empty($weight)) {
                    $fail('The specification list must contain a valid "Weight" value.');
                }
            }],
            'specification.*.value' => 'required_with:specification.*.name|filled',
            'specification.*.name' => 'required',
//            'started_at'            => 'required_if:schedule,1|date|after:yesterday|before:expired_at',
            'started_at' => 'date|nullable',//todo correct date validation
            'image' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'event_id' => 'required|exists:events,id',
            'less_bidding_value' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'max_auto_bid_price' => 'nullable',
            'max_auto_bid_steps' => 'nullable',
            'color_class' => 'nullable',
        ];
    }
    public function validation($request, $imgValidation){

        return $request->validate($this->getValidationRules($imgValidation));
    }
    public function get_log_file($uuid,$option){

        if(config('filesystems.default') == 's3'){
            $client = $this->getS3Client();
            $client->registerStreamWrapper();
            $path = Storage::path('/ImportFiles/products/'.$uuid.'.txt');
            $s3FilePath = 's3://' . config('filesystems.disks.s3.bucket') . '/' . $path;
            if(!Storage::exists($path)){
                return "Import products are pushed to queue and will start soon...";
            }
            if($option=='down'){
                return route('admin.download',['ImportFiles','products',basename($path)]);
            }
            return file_get_contents($s3FilePath);
        }

        if(config('filesystems.default') == 'local'){
            $path = Storage::path('/ImportFiles/products/'.$uuid.'.txt');
            if(!Storage::exists('/ImportFiles/products/'.$uuid.'.txt')){
                return "Import products are pushed to queue and will start soon...";
            }

            if($option=='down'){
                return route('admin.download',['ImportFiles','products',basename($path)]);
            }
            return file_get_contents($path);
        }
    }

    /**
     * @return S3Client
     */
    public function getS3Client(): S3Client
    {
        $client = new S3Client([
            'version' => config('sqs.version'),
            'region' => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
        return $client;
    }

    /**
     * @param $img
     * @return string
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function saveImageAndReturnName($img): string
    {
        return uploadImageToS3($img, imagePath()['product']['path'],
            imagePath()['product']['size'],
            $img,
            imagePath()['product']['thumb'],
            true,
            imagePath()['product']['size_sm'],
            imagePath()['product']['size_md']);
    }


    public function duplicateProduct($id){

        $product=Product::find($id);
        $duplicated_product=$product->replicate();
        $duplicated_product->save();

        foreach($product->product_specification as $spec){
            $duplicated_product->product_specification()->create($spec->toArray());
        }

    }

    public function deleteProduct($id){

        $product=Product::find($id);
        if($product->product_specification()->count()){
            $product->product_specification()->delete();
        }

        $product->delete();

    }
}

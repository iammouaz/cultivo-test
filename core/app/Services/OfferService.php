<?php

namespace App\Services;


use App\Exceptions\OfferValidationException;
use App\Models\Bid;
use App\Models\OfferSheet;
use App\Models\Merchant;
use App\Models\Offer;
use App\Models\OfferSpecification;
use App\Models\Review;
use App\Rules\FileTypeValidate;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Rap2hpoutre\FastExcel\FastExcel;
use Sentry\Laravel\Integration;
use Illuminate\Support\Facades\File;

class OfferService extends BaseService{
    /**
     * @param $search_key
     * @param $category_id
     * @return array
     */
    public function getOffers($search_key, $category_id)
    {
        $offers = Offer::live();
        $offers = $offers->where('name', 'like', '%' . $search_key . '%');


        if ($category_id) {
            $offers = $offers->where('category_id', $category_id);
        }
        $minPrice = $offers->clone()->min('price');
        $maxPrice = $offers->clone()->max('price');
        $offers = $offers->paginate(getPaginate(18));
        return [$offers, $minPrice, $maxPrice];
    }
    /**
     * @param array $validated
     * @return mixed
     */
    public function getOffersFiltered( $validated)
    {
        $offers = Offer::live()->where('name', 'like', '%' . $validated['search_key'] . '%');

        if ($validated['sorting']) {
            $offers->orderBy($validated['sorting'], 'ASC');
        }
        if ($validated['categories']) {
            $offers->whereIn('category_id', $validated['categories']);
        }
        if ($validated['minPrice']) {
            $offers->where('price', '>=', $validated['minPrice']);
        }
        if ($validated['maxPrice']) {
            $offers->where('price', '<=', $validated['maxPrice']);
        }
        if ($validated['country'] && $validated['country'] != "All") {
            $offers->where('specification->5->value', 'like', '%' . $validated['country']);
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

            $offers->where('specification->2->value', '>=', $down_s)->where('specification->2->value', '<=', $up_s);
        }
        if ($validated['location'] && $validated['location'] != "All") {
            $offers->where('specification->6->value', 'like', '%' . $validated['location']);
        }
        $offers = $offers->paginate(getPaginate(18));
        return $offers;
    }

    public function offerDetails($id,$user_id=null)
    {
        $offer = Offer::with('merchant')->where('status', '!=', 0)->findOrFail($id);
        $relatedOffers = Offer::live()->where('id', '!=', $id)->where('offer_sheet_id',$offer->offer_sheet_id)->limit(10)->get();
        $offerSheet = OfferSheet::find($offer->offer_sheet_id);
        $imageData = imagePath()['product'];

        $seoContents = getSeoContents($offer, $imageData, 'image');
        return [
            $offer,
            $relatedOffers,
            $seoContents,
            $offerSheet
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
     * @param $offer_id
     * @param $rating
     * @param $description
     * @return bool
     */
    public function createOrUpdateOfferReview($offer_id, $rating, $description)
    {
        Bid::where('user_id', auth()->id())->where('offer_id', $offer_id)->firstOrFail();


        $review = Review::where('user_id', auth()->id())->where('offer_id', $offer_id)->first();
        $offer = Offer::find($offer_id);

        if (!$review) {
            $review = new Review();
            $offer->total_rating += $rating;
            $offer->review_count += 1;
            $updated = false;
        } else {
            $offer->total_rating = $offer->total_rating - $review->rating + $rating;
            $updated = true;
        }

        $offer->avg_rating = $offer->total_rating / $offer->review_count;
        $offer->save();

        $review->rating = $rating;
        $review->description = $description;
        $review->user_id = auth()->id();
        $review->offer_id = $offer_id;
        $review->save();
        return $updated;
    }
    public function importOfferFromCsv($fileCSV, $uuid, $original_fileName)
    {
        if(config('filesystems.default') == 's3'){
            $client = $this->getS3Client();
            $client->registerStreamWrapper();
            $s3FilePath = 's3://' . config('filesystems.disks.s3.bucket') . '/' . $fileCSV;
            $CSV=file_get_contents($s3FilePath);
            Storage::disk('local')->put('/ImportFiles/offers/'.basename($s3FilePath) ,$CSV);
            $fileCSV=Storage::disk('local')->path('/ImportFiles/offers/'.basename($s3FilePath));
        }
        $orginal_file = new UploadedFile(
            $fileCSV,
            basename($fileCSV), // The original file name
            mime_content_type($fileCSV), // The file's MIME type
            filesize($fileCSV), // The file's size in bytes
            true, // error status (can be null)
            true // test mode, set to true to profferSheet the file from being moved
        );
        $default_image_file = new UploadedFile(
            public_path('assets/images/product_import_default_image.png'),
            'product_import_default_image.png', // The original file name
            mime_content_type(public_path('assets/images/product_import_default_image.png')), // The file's MIME type
            filesize(public_path('assets/images/product_import_default_image.png')), // The file's size in bytes
            true, // error status (can be null)
            true // test mode, set to true to profferSheet the file from being moved
        );

        $counter = 0; // counter for offers added
        $rowNumber = 1; // row number in csv file
        $log = "";  // log file
        $log .= "Start DateTime: ".Carbon::now()."\n";
        $log .= "Csv File Name: ".$original_fileName."\n";
        $log .= "Csv File Size: ".$orginal_file->getSize()/ (1024 * 1024)." Mega Bytes\n";
        $log .= "................................\n";
        $counterFailed = 0; // counter for offers failed

        $path ="ImportFiles/offers/".$uuid.".txt";

        Storage::put($path, $log);

        $savedOffers=collect();
        // read csv file
        $offers = (new FastExcel)->configureCsv(',', '"', 'UTF-8')
            ->import($fileCSV, function ($line) use
            ($default_image_file, &$counter, &$log, &$counterFailed, &$rowNumber , $path, $savedOffers) {


                $rowNumber++; // increment row number

                // trim all data in row and replace default values with nulls
                $line = array_map(function ($value) {
                    $trim = trim($value);
                    return $trim == '' ? null : $trim;
                }, $line);
                //extract spread specs data to specification field in input
                $line['specification']=$this->extractSpecificationFields($line);
                //validate data in row
                $validator = Validator::make($line, array_merge([
                    'price/lb' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
                    'size name' => ['required',(new Exists('sizes','size'))->where('deleted_at',null)->where('offer_sheet_id',$line['offer_sheet_id']),
                      ],
                ],$this->getSpecsValidation(),$this->getValidationRules('nullable')));
                $existingOffer = $savedOffers->where('name', $line['name'])->first();
                $validator->after(function ($validator) use($line,$existingOffer) {
                    if($line['size name'] && $existingOffer && $existingOffer->prices()->whereHas('size',function($q) use($line){
                            $q->where('size',$line['size name'])->where('offer_sheet_id',$line['offer_sheet_id']);
                        })->count()>0) {
                        $validator->errors()->add('size name', 'Offer with this name and size already exists');
                    }
                });
                // if validation fails
                if ($validator->fails()) {
                    // Get all validation errors
                    $fields = $validator->messages()->get('*');
                    try {
                        // Log the errors
                        $log = "row #$rowNumber :Validation errors for offer: " . ($line['name']??'Undefined') . ":\n";
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
                    $counterFailed++; // increment counter for offers failed
                } else {
                    try {
                        $offer=$existingOffer;
                        if(!$offer) {
                            $offer = $this->createOffer($line, $this->saveImageAndReturnName($default_image_file));
                            $savedOffers->add($offer);
                        }
                        $this->addSizePrice($offer,$line['size name'],$line['price/lb']);
                        $counter++;// increment counter for accounts added
                    } catch (\Exception $e) {
                        // Log the errors
                        $log = "row #$rowNumber : unexpected  error for offer: " . ($line['name']??'Undefined') . ":\n";
                        $log .= " - " . $e->getMessage() . "\n";
                        Storage::append($path, $log); // save log file
                        $counterFailed++; // increment counter for offers failed
                        Log::error($e);
                        Integration::captureUnhandledException($e);
                    }
                }

            });// end read csv file
        // add log
        $log = "................................\n";
        $log .= "End DateTime: " . Carbon::now()."\n";
        $log .= "Total rows: " . ($rowNumber - 1) . "\n";
        $log .= "Total offers added: " . $counter . "\n";
        $log .= "Total offers failed: " . $counterFailed . "\n";
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
                throw new OfferValidationException('Image could not be uploaded.');
            }
        }
        return null;
    }
    public function createOffer($validated,$image)
    {
        $offer = new Offer();
        $offer->status = 1;
        return $this->saveOffer($validated, $offer,$image);

    }
    public function saveOffer($data, $offer,$image)
    {
        if(!isset($data['merchant_id']) || !$data['merchant_id']){
            $offer->admin_id  = auth()->guard('admin')->id();
        }else{
            $offer->admin_id  = 0;
            $offer->merchant_id  = $data['merchant_id'];
        }
        if($image) {
            $offer->image = $image;
        }
        $offer->name = $data['name'];
        $offer->short_description = $data['short_description'];
        $offer->long_description = $data['long_description'];
        $offer->offer_sheet_id = $data['offer_sheet_id'];
        $offer->save();

        if ($data['specification']) {//todo sync specifications instead of this method
            $specifications = OfferSpecification::where('offer_id', $offer->id)->get();
            if ($specifications->isNotEmpty()) {
                OfferSpecification::where('offer_id', $offer->id)->delete();
            }
            foreach ($data['specification'] as $key => $spec) {
                $specification = new OfferSpecification;
                $specification->offer_id = $offer->id;
                $specification->spec_key = $spec['name'];
                $specification->Value = $spec['value'];
                $specification->is_display = $spec['is_display'];
                $specification->save();
            }
        }


        clear_all_cache();
        return $offer;
    }

    private function extractSpecificationFields(array $line)
    {
        $specificationList = ['Harvest', 'Tasting Notes', 'Location',
            'Diff / 100 Lb', 'NY KCH24 @ 185', 'Score', 'Status',
            'Units Available', 'Origin', 'Producer', 'Region', 'Altitude',
            'Variety', 'Processing Method', 'Drying', 'Grade', 'Screen'];
        $specificationsKeyValueArray=[];
        foreach ($specificationList as $specification) {
            if (!empty($line[$specification])) {
                $specificationsKeyValueArray[] = ['name'=>$specification,'value'=>$line[$specification],'is_display'=>1];
            }
        }

        return $specificationsKeyValueArray;
    }

    public function validation($request, $imgValidation){

        return $request->validate($this->getValidationRules($imgValidation));
    }
    public function get_log_file($uuid,$option){

        if(config('filesystems.default') == 's3'){
            $client = $this->getS3Client();
            $client->registerStreamWrapper();
            $path = Storage::path('/ImportFiles/offers/'.$uuid.'.txt');
            $s3FilePath = 's3://' . config('filesystems.disks.s3.bucket') . '/' . $path;
            if(!Storage::exists($path)){
                return "Import offers are pushed to queue and will start soon...";
            }
            if($option=='down'){
                return route('admin.download',['ImportFiles','offers',basename($path)]);
            }
            return file_get_contents($s3FilePath);
        }

        if(config('filesystems.default') == 'local'){
            $path = Storage::path('/ImportFiles/offers/'.$uuid.'.txt');
            if(!Storage::exists('/ImportFiles/offers/'.$uuid.'.txt')){
                return "Import offers are pushed to queue and will start soon...";
            }

            if($option=='down'){
                return route('admin.download',['ImportFiles','offers',basename($path)]);
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

    private function getValidationRules($imgValidation)
    {
        return [
            'name' => 'required',
            'merchant_id' => 'nullable',
            'short_description' => 'nullable',
            'long_description' => 'nullable',
            'specification' => ['required','array',function ($attribute, $value, $fail) {
//                $weight = collect($value)->where('name','Weight')->first()['value']??null;
////                Log::info($weight);
//                if (empty($weight)) {
//                    $fail('The specification list must contain a valid "Weight" value.');
//                }
            }],
            'specification.*.value' => 'required_with:specification.*.name|filled',
            'specification.*.name' => 'required',
            'image' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'offer_sheet_id' => 'required|exists:offer_sheets,id',
        ];
    }

    /**
     * @return string[]
     */
    function getSpecsValidation(): array
    {
        return ['Units Available' => 'required',
            'Origin' => 'required',
            'Producer' => 'required',
            'Region' => 'required',
            'Altitude' => 'nullable',
            'Variety' => 'nullable',
            'Processing Method' => 'required',
            'Drying' => 'nullable',
            'Grade' => 'required',
            'Screen' => 'required',
            'Harvest' => 'nullable',
            'Tasting Notes' => 'required',
            'Location' => 'required',
            'Diff / 100 Lb' => 'required',
            'NY KCH24 @ 185' => 'required',
            'Score' => 'required',];
    }

    private function addSizePrice($offer, $size, $price)
    {
        $size = $offer->offerSheet->sizes()->where('size', $size)->first();
        $size->prices()->create([
            'price' => $price,
            'offer_id' => $offer->id,
        ]);
    }

}

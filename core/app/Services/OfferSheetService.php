<?php

namespace App\Services;

use App\Exceptions\OfferSheetValidationException;
use App\Models\Category;
use App\Models\OfferSheet;
use App\Models\Fee;
use App\Models\ShippingRanges;
use App\Models\ShippingRegion;
use App\Models\Size;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;

class OfferSheetService extends BaseService
{
    /**
     * @param $with
     * @param $paginate
     * @return LengthAwarePaginator
     */
    public function latestPaginatedWith($with = [], $paginate = null)
    {
        $paginate ??= $this->paginationSize;
        return $this->offerSheetRepository->query()->with($with)->latest()->paginate($paginate);
    }
    public function getById($id, $with = [])
    {
        return $this->offerSheetRepository->query()->with($with)->where('id', $id)->first();
    }
    public function getCategories()
    {
        return $this->categoryRepository->getAll();
    }
    public function getOfferSheetCategories()
    {
        return $this->categoryRepository->query()->whereIn('name', ['Fixed Price', 'offer sheet'])->get(); //todo remove 'offer sheet' element
    }
    public function getRegions()
    {
        return $this->regionRepository->getAll();
    }
    public function getCountries()
    {
        return $this->countryRepository->getAll();
    }
    public function getOrigins()
    {
        return $this->originRepository->getAll();
    }
    public function search($search)
    {
        return $this->offerSheetRepository->query()->where(function ($offerSheet) use ($search) {
            $offerSheet->where('name', 'like', "%$search%");
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

    public function duplicateOfferSheet($id)
    {
        $offerSheet = OfferSheet::find($id);
        $duplicated_offer_sheet = $offerSheet->replicate();
        $duplicated_offer_sheet->name = $offerSheet->name . ' - Copy';
        $duplicated_offer_sheet->offer_sheet_url = $offerSheet->offer_sheet_url . '-copy';

        $duplicated_offer_sheet->save();

        foreach ($offerSheet->sizes as $size) {
            $duplicated_offer_sheet->sizes()->create($size->toArray());
        }

        foreach ($offerSheet->files as $file) {
            $duplicated_offer_sheet->files()->create($file->toArray());
        }

        foreach ($offerSheet->shippingRegions as $region) {
            $duplicated_region = $duplicated_offer_sheet->shippingRegions()->create($region->toArray());
            foreach ($region->shippingRanges as $range) {
                $duplicated_region->shippingRanges()->create($range->toArray());
            }
        }

        foreach ($offerSheet->fees as $fee) {
            $duplicated_offer_sheet->fees()->create($fee->toArray());
        }

        foreach ($offerSheet->offers as $offer) {
            $duplicated_offer = $duplicated_offer_sheet->offers()->create($offer->toArray());
            foreach ($offer->offer_specification as $spec) {
                $duplicated_offer->offer_specification()->create($spec->toArray());
            }
            foreach ($offer->prices as $price) {
                $duplicated_offer->prices()->create($price->toArray());
            }
            foreach ($offer->files as $file) {
                $duplicated_offer->files()->create($file->toArray());
            }
        }
    }

    public function saveOfferSheet($request, $offerSheet) //todo use only validated request items
    {
        if ($request->hasFile('image')) {
            try {
                $offerSheet->image = uploadImageToS3(
                    $request->image,
                    imagePath()['event_banner_image']['path'],
                    imagePath()['event_banner_image']['size'],
                    $offerSheet->image,
                    imagePath()['event_banner_image']['thumb'],
                    true,
                    imagePath()['event_banner_image']['size_sm'],
                    imagePath()['event_banner_image']['size_md']
                );
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new OfferSheetValidationException('Image could not be uploaded.');
            }
        }
        if ($request->hasFile('banner_logo')) {
            try {
                $offerSheet->banner_logo = uploadImageToS3($request->banner_logo, imagePath()['event_logo']['path'], imagePath()['event_logo']['size'], $offerSheet->banner_logo, imagePath()['event_logo']['thumb']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new OfferSheetValidationException('Image could not be uploaded.');
            }
        }

        if ($request->hasFile('card_logo')) {
            try {
                $offerSheet->card_logo = uploadImageToS3($request->card_logo, imagePath()['event_logo']['path'], imagePath()['event_logo']['size'], $offerSheet->card_logo, imagePath()['event_logo']['thumb']);
            } catch (\Exception $exp) {
                Log::error($exp);
                Integration::captureUnhandledException($exp);
                throw new OfferSheetValidationException('Image could not be uploaded.');
            }
        }

        $offerSheet->name = $request->name ?? null;
        $offerSheet->sname = $request->sname ?? null;
        $offerSheet->description = $request->description ?? null;
        $offerSheet->deposit = $request->deposit ?? null;
        $offerSheet->category_id = $request->category ?? Category::firstOrCreate(['name' => 'Fixed Price'])->id;
        //remove the app_url from the beginning of the url
        $url = $request->url;
        if ($url) {
            $url = str_replace(config('app.url') . '/', '', $url);
        } else {
            do {
                $url = random_int(100000, 999999);
            } while (OfferSheet::query()->where('offer_sheet_url', $url)->exists()); //although unlikely to happen but this is safer
        }
        $offerSheet->offer_sheet_url = $url;
        $offerSheet->emails = $request->emails;
        $offerSheet->show_add_order_button = $request->order ?? 0;
        $offerSheet->show_make_offer_button = $request->offer ?? 0;
        $offerSheet->show_add_sample_button = $request->sample ?? 0;
        $offerSheet->hero_show_action_name = $request->hero_show_action_name ?? 0;
        $offerSheet->hero_text_color = json_encode($request->hero_text_color ?? []);
        $offerSheet->hero_primary_button_color = json_encode($request->hero_primary_button_color ?? []);
        $offerSheet->hero_image_overlay = json_encode($request->hero_image_overlay ?? []);
        $offerSheet->hero_outlined_button_color = json_encode($request->hero_outlined_button_color ?? []);
        $offerSheet->save();
        $offerSheet->origins()->sync($request->origin);
        clear_all_cache();
    }

    public function get_sizes($id)
    {

        $sizes = Size::where('offer_sheet_id', $id)->select('id', 'size', 'weight_LB', 'additional_cost')->get();

        return $sizes;
    }

    public function saveFee($request, $offerSheet) //todo use only validated request items
    {
        if ($request->has('fees')) {
            foreach ($request->fees as $key => $element) {
                $fee = new Fee();
                $fee->event_id = $offerSheet->id;
                $fee->event_type = get_class($offerSheet);
                $fee->country_id = $element["country_id"];
                $fee->fee_value = $element["fee_value"];
                $fee->payment_method = $element["payment_method"];
                $fee->save();
            }
        }
    }
    public function saveregion($request, $offerSheet) //todo use only validated request items
    {
        if ($request->shippingregions) {
            foreach ($request->shippingregions as $key => $oneregion) {
                $shippingregion = new ShippingRegion();

                $shippingregion->event_id = $offerSheet->id;
                $shippingregion->event_type = get_class($offerSheet);
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
    public function addOfferSheet($request)
    {
        $this->validation($request, 'required');
        if (Carbon::parse($request->end_date)->diffInMinutes(Carbon::parse($request->start_date)) < $request->less_bidding_time) {
            throw new OfferSheetValidationException('Less Bidding Time must be Lesser than OfferSheet Period');
        }
        $offerSheet = new OfferSheet();
        DB::transaction(function () use ($request, $offerSheet) {
            $this->saveOfferSheet($request, $offerSheet);
            // save sizes in $offerSheet->sizes
            $this->InsertOrUpdateSizes($request, $offerSheet);
            $this->saveregion($request, $offerSheet);
            $this->saveFee($request, $offerSheet);
        });
        return $offerSheet;
    }
    protected function validation($request, $imgValidation)
    {
        // $url = $request->url;
        // if ($url) {
        //     $url = str_replace(config('app.url') . '/', '', $url);
        // } else {
        //     do {
        //         $url = random_int(100000, 999999);
        //     } while (OfferSheet::query()->where('offer_sheet_url', $url)->exists()); //although unlikely to happen but this is safer
        // }
        // $request->merge(['url' => $url]);
        //        dd($request->all());
        $request->validate([
            'name' => 'required',
            //            'category' => 'required|exists:categories,id',
            'description' => 'required',
            'deposit' => 'nullable',
            'image' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'banner_logo' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png']), "nullable"],
            'card_logo' => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png']), "nullable"],
            'url' => 'required|unique:offer_sheets,offer_sheet_url,' . $request->input('id'),
            'sizes' => 'required',
            'shippingranges' => 'required',
            'fees' => 'required',
            'shippingregions' => 'required',
            'shippingranges.*.*.from' => [
                'required',
                'numeric',

                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('The Shipping Ranges From(Lb) field must be more than 0.');
                    }
                },
            ],
            'shippingranges.*.*.up_to' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('The Shipping Ranges Up To(Lb) field must be more than 0.');
                    }
                },
            ],
            'sizes.*.weight' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('The Weight(Lb) field must be more than 0.');
                    }
                },
            ],
            'shippingranges.*.*.cost' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('The Shipping Ranges Cost field must be more than 0.');
                    }
                },
            ],
            'hero_show_action_name' => 'boolean | nullable',
            'hero_text_color' => 'nullable',
            'contained_button_color' => 'nullable',
            'outlined_button_color' => 'nullable',
            'hero_image_overlay' => 'nullable',

        ], [
            'shippingregions.required' => 'The Shipping Regions field is required.',
            'shippingranges.required' => 'The Shipping Ranges field is required.',

        ]);
    }
    public function editOfferSheet($request, $id)
    {
        $this->validation($request, 'nullable');
        $offerSheet = $this->getById($id, ['offers']);
        if (is_null($offerSheet)) {
            throw new OfferSheetValidationException('OfferSheet not found');
        }
        DB::transaction(function () use ($request, $offerSheet, $id) {
            $this->saveOfferSheet($request, $offerSheet);
            $this->InsertOrUpdateSizes($request, $offerSheet);
            $this->updateregion($request, $offerSheet, $id);
            $this->updateFee($request, $offerSheet);
        });
        clear_all_cache();
    }

    public function deleteOfferSheet($id)
    {

        $offerSheet = OfferSheet::find($id);
        if ($offerSheet->offers()->count()) {
            foreach ($offerSheet->offers as $offer) {
                if ($offer->prices()->count()) {

                    $offer->prices()->delete();
                }
                if ($offer->files()->count()) {
                    $offer->files()->delete();
                }
                if ($offer->offer_specification()->count()) {
                    $offer->offer_specification()->delete();
                }
                $offer->delete();
            }
        }
        if ($offerSheet->sizes()->count()) {

            $offerSheet->sizes()->delete();
        }
        if ($offerSheet->shippingRegions()->count()) {
            foreach ($offerSheet->shippingRegions as $region) {
                if ($region->shippingRanges()->count()) {
                    $region->shippingRanges()->delete();
                }
                $region->delete();
            }
        }
        if ($offerSheet->fees()->count()) {
            $offerSheet->fees()->delete();
        }
        if ($offerSheet->files()->count()) {
            $offerSheet->files()->delete();
        }
        $offerSheet->delete();
    }
    public function UpdateFee($request, $offerSheet)
    {
        $offerSheet->fees()->delete();
        if ($request->has('fees')) {
            $this->saveFee($request, $offerSheet);
        }
    }
    public function InsertOrUpdateSizes($request, $offerSheet)
    {
        if ($request->has('sizes')) {
            // if size duplicate in request->sizes throw exception
            $sizeValidation = array();
            foreach ($request->sizes as $key => $value) {
                if (in_array($value['size'], $sizeValidation)) {
                    throw new OfferSheetValidationException('The Size field is duplicated.');
                }
                $sizeValidation[] = $value['size'];
            }
            // Delete records that are not present in the current request
            $existingIds = collect($request->sizes)->pluck('id')->filter();
            foreach ($offerSheet->sizes()->whereNotIn('id', $existingIds)->get() as $size) {
                if ($size->prices()->count() == 0)
                    $size->delete();
            }
            foreach ($request->sizes as $size) {

                $sizeId = $size["id"] ?? null; // Assuming you have the ID of the Sizes record
                if ($sizeId) {
                    $data = [
                        'size' => $size["size"],
                        'weight_LB' => $size["weight"],
                        'additional_cost' => $size["additional_cost"]??0,
                        'is_sample' => array_key_exists('is_sample', $size) ? 1 : 0,
                    ];
                    // Assuming $offerSheet is an instance of your model
                    $offerSheet->sizes()->where('id', $sizeId)->update($data);
                } else {
                    // Create a new record
                    $data = [
                        'size' => $size["size"],
                        'weight_LB' => $size["weight"],
                        'additional_cost' => $size["additional_cost"]??0,
                        'is_sample' => array_key_exists('is_sample', $size) ? 1 : 0,
                    ];
                    $offerSheet->sizes()->create($data);
                }
            }
        }
    }

    public function updateregion($request, $offerSheet, $id)
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
                // Check if the key exists in the shippingranges array
                if (!array_key_exists($key, $request->shippingranges) || $request->shippingranges[$key] == null) {
                    throw new OfferSheetValidationException('The Shipping Ranges field is required.');
                }
                $shippingregion = new ShippingRegion();

                $shippingregion->event_id = $offerSheet->id;
                $shippingregion->event_type = get_class($offerSheet);
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
}

<?php

namespace App\Http\Controllers;

use App\Models\OfferSheet;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * @var OfferService
     */
    protected $offerService;
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->offerService = app('offerService');

    }
    private function toSnakeCase($sentence)
    {
        return strtolower(str_replace(' ', '_', $sentence));
    }

    public function activeOffers($url)
    {

        $offerSheet = OfferSheet::query()->where('offer_sheet_url', $url)->first();
        if (!$offerSheet) {
            abort(404);
        }
        list($pageTitle, $emptyMessage, $offers,$term) = $this->searchOffersQuery($offerSheet,[]);
        return view($this->activeTemplate . 'offer_sheet.details', compact('pageTitle',
            'offerSheet','emptyMessage', 'offers', 'term','url'));
    }
    public function activeOffersById($id)
    {
        $offerSheet = OfferSheet::query()->where('id', $id)->first();
        return redirect()->route('offer_sheet.activeOffers', $offerSheet->offer_sheet_url);
    }

    public function activeOffersTableView($url)
    {
        $offerSheet = OfferSheet::query()->where('offer_sheet_url', $url)->first();
        if (!$offerSheet) {
            abort(404,'The offer sheet url is not valid');
        }
        $validated = request()->validate([
            'search_key' => 'nullable|string',
            'currentPage' => 'nullable|integer',
            'perPage' => 'nullable|integer',
            'filter' => 'nullable|string',
        ]);
        list($pageTitle, $emptyMessage, $offers,$term) = $this->searchOffersQuery($offerSheet,$validated);
        $offerTableView =  view($this->activeTemplate . 'offer_sheet.offers_table', compact('pageTitle', 'emptyMessage', 'offers','term'))->render();
        return response()->json(['offerTableView' => $offerTableView]);
    }

    public function filter(Request $request)
    {
        $pageTitle = 'Search Offers';
        $emptyMessage = 'No offer found';
        $offers = Offer::live()->where('name', 'like', '%' . $request->search_key . '%');

        if ($request->sorting) {
            $offers->orderBy($request->sorting, 'ASC');
        }
        if ($request->categories) {
            $offers->whereIn('category_id', $request->categories);
        }
        if ($request->minPrice) {
            $offers->where('price', '>=', $request->minPrice);
        }
        if ($request->maxPrice) {
            $offers->where('price', '<=', $request->maxPrice);
        }
        if ($request->country && $request->country != "All") {
            $offers->where('specification->5->value', 'like', '%' . $request->country);
        }
        if ($request->score && $request->score != "All") {
            if ($request->score == "80-83") {
                $down_s = 80;
                $up_s = 83;
            } elseif ($request->score == "84-86") {
                $down_s = 84;
                $up_s = 86;
            } else {
                $down_s = 86;
                $up_s = 10000;
            }

            $offers->where('specification->2->value', '>=', $down_s)->where('specification->2->value', '<=', $up_s);
        }
        if ($request->location && $request->location != "All") {
            $offers->where('specification->6->value', 'like', '%' . $request->location);
        }
        $offers = $offers->paginate(getPaginate(18));

        return view($this->activeTemplate . 'offer.filtered', compact('pageTitle', 'emptyMessage', 'offers'));
    }

    public function offerDetails($id)
    {
        $pageTitle = 'Offer Details';
        $user_id = Auth::id();
        [$offer, $relatedOffers, $seoContents,$offerSheet] = $this->offerService->offerDetails($id,$user_id);

        $data = compact('pageTitle', 'offer', 'relatedOffers', 'seoContents', 'offerSheet');
        return view($this->activeTemplate . 'offer.details', $data);
    }

    private function getOperatorAndOperands($data)
    {
        $column = $data['column'];

        $operatorName = $data['operator'];
        $value = $data['value'];
        $isColumn = in_array($column, ['name','Product Name']);
        $isPrice = in_array($column, ['price','Price/Lb']);
        $isUnitSize = in_array($column,["Unit Size"]);
        $isSpec = in_array(strtoupper($column), ['COUNTRY','ALTITUDE','DRYING','GRADE','HARVEST','LOCATION','PRODUCER','REGION','SCORE','SCREEN','TASTE','VARIETY','UNITS AVAILABLE',"ORIGIN","PROCESSING METHOD"]);
        $operator = '=';
        if($isColumn) $column = 'name';
        if($isPrice) $column = 'price';
        if($isUnitSize)$column = 'size';
        if ($operatorName == 'equal') {
            $operator = '=';
        } elseif (strtolower($operatorName) == 'contains' || strtolower($operatorName) == "contain") {
            $operator = 'like';
            $value = '%' . $value . '%';
        }

        return [$column, $operator, $value, $isColumn, $isPrice, $isSpec,$isUnitSize];
    }
    /**
     * @return array
     */
    public function searchOffersQuery($offerSheet,$data): array
    {


        $term = request()->search_key;
        $pageTitle = $term ? 'Search Offers' : 'Specialty Coffees You can Buy Now';
        $emptyMessage = 'No offer found';

        /*
         * @var $offers QueryBuilder
         */
        // $offers = Offer::query()->with(['prices', 'offer_specification']);
        $offers = Offer::with('offer_specification','prices','prices.size');
//            ->Join('prices', function ($join) {
//                $join->on('offers.id', '=', 'prices.offer_id');
//                $join->where('prices.deleted_at', '=', null);
//            })
//        ->Join('sizes', function ($join) {
//            $join->on('prices.size_id', '=', 'sizes.id');
//            $join->where('sizes.deleted_at', '=', null);
//        })
//        ->orderBy('prices.price')
//        ->selectRaw('offers.*,offers.id as offer_id,prices.id as price_id,prices.price as size_price,prices.size_id,sizes.id as size_id,sizes.weight_LB as sizes_weight,sizes.is_sample as is_sample');
        if(isset($offerSheet))
            $offers->where('offers.offer_sheet_id', $offerSheet->id);
        $offers->where('name', 'like', '%' . $term . '%');

        if(isset($data['filter']) && count($filter=json_decode($data['filter'],true))>0){
            foreach ($filter as $item){
                [$key,$operator,$value,$isColumn,$isPrice,$isSpec,$isUnitSize]=$this->getOperatorAndOperands($item);

                if($isColumn)
                    $offers->where($key, $operator, $value);
                elseif($isPrice)
                    $offers->whereHas('prices',function ($query) use ($key,$operator,$value){
                        $query->where($key, $operator, $value);
                    });

                elseif($isUnitSize)
                    $offers->whereHas('prices.size',function ($query) use ($key,$operator,$value){
                        $query->where($key, $operator, $value);
                    });

                elseif($isSpec)
                    $offers->whereHas('offer_specification',function ($query) use ($key,$operator,$value){
                        $query->where('spec_key', $operator, $key)
                            ->where('Value', $operator, $value);
                    });
            }
        }


        if (request()->category_id) {
            $offers = $offers->where('category_id', request()->category_id);
        }
        $offers = $offers->paginate($data['perPage']??getPaginate(20), ['*'], 'page', $data['currentPage']??1);
        foreach ($offers as $offer) {
            foreach ($offer->offer_specification()->get() as $offer_specification) {
                if (!($offer_specification->spec_key) || !($offer_specification->Value))
                    continue;
                $spec_key = $this->toSnakeCase($offer_specification->spec_key);
                $spec_value = $offer_specification->Value;
                $offer->$spec_key = $spec_value;
            }
            $offer->price_new = $offer->prices->max('price');
            $offer->order_prices = $offer->prices->where('size.is_sample', '!=', 1)->sortBy('price');
            $offer->sample_price = $offer->prices->where('size.is_sample', 1)->first();
        }

        return array($pageTitle, $emptyMessage, $offers,$term);
    }


}

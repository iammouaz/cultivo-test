<?php

namespace App\Http\Controllers;

use App\Exceptions\BidValidationException;
use App\Services\BidService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * @var BidService
     */
    protected $bidService;
    /**
     * @var ProductService
     */
    protected $productService;
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->bidService = app('bidService');
        $this->productService = app('productService');
    }

    public function products()
    {
        $search_key = request()->search_key;
        $category_id = request()->category_id;
        $pageTitle = $search_key ? 'Search Products' : 'Specialty Coffees You can Buy Now';
        $emptyMessage = 'No product found';


        [$products,$minPrice,$maxPrice]= $this->productService->getProducts($search_key, $category_id);


        return view($this->activeTemplate . 'product.list', compact('pageTitle', 'emptyMessage', 'products','minPrice','maxPrice'));
    }

    public function filter(Request $request)
    {
        $pageTitle = __('Search Products');
        $emptyMessage = __('No product found');
        $validated = $request->validate([
            'search_key' => 'nullable',
            'sorting' => 'nullable|in:price,created_at',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|integer|gt:0',
            'minPrice' => 'nullable|numeric|gt:0',
            'maxPrice' => 'nullable|numeric|gt:0',
            'country' => 'nullable|string',
            'score' => 'nullable|string',
            'location' => 'nullable|string',
        ]);
        $products = $this->getProductsFiltered($validated);

        return view($this->activeTemplate . 'product.filtered', compact('pageTitle', 'emptyMessage', 'products'));
    }

    public function productDetails($id)
    {
        $pageTitle = __('Auction Details');

        $user_id = Auth::id();
        [$product, $relatedProducts, $seoContents,$event,$new_bidding_value,$amount,$autosettings] = $this->productService->productDetails($id,$user_id);

        $data = compact('pageTitle', 'product', 'relatedProducts', 'seoContents', 'event', 'new_bidding_value');
        if($autosettings){
            $data['autosettings']=$autosettings;
        }
        return view($this->activeTemplate . 'product.details', $data);
    }


    public function loadMore(Request $request)//todo check the need for this function (its route is not being called in front)
    {
        $reviews = $this->productService->loadReviews($request->bid);
        return view($this->activeTemplate . 'partials.product_review', compact('reviews'));
    }



    public function bid(Request $request)//todo check the need for this function, it is not being called in front
    {
        $validated=$request->validate([
            'amount' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'product_id' => 'required|integer|gt:0',
            'max_value' => 'nullable|gt:0',
            'step' => 'nullable|gt:0',
        ]);

        try {
            $this->bidService->bidProduct($validated['product_id'], $validated['amount'], $validated['max_value'], $validated['step'],$request);
        }
        catch (BidValidationException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', __('Successful Bid')];
        return back()->withNotify($notify);
    }





    public function saveProductReview(Request $request)
    {

        $validated=$request->validate([
            'rating' => 'required|integer|between:1,5',
            'product_id' => 'required|integer',
            'description' => 'nullable|string'

        ]);

        $updated = $this->productService->createOrUpdateProductReview($validated['product_id'], $validated['rating'], $validated['description']);

        if ($updated) {
            $notify[] = ['success', 'Review updated successfully'];
        }else{
            $notify[] = ['success', 'Review given successfully'];
        }
        return back()->withNotify($notify);
    }

    public function saveMerchantReview(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'merchant_id' => 'required|integer',
            'description' => 'nullable|string'
        ]);
        $updated = $this->productService->createOrUpdateMerchantReview($validated['merchant_id'], $validated['rating'], $validated['description']);
        if ($updated) {
            $notify[] = ['success', 'Review updated successfully'];
        }else{
            $notify[] = ['success', 'Review given successfully'];
        }
        return back()->withNotify($notify);
    }




}

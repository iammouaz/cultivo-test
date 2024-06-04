<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferSheet extends Model
{
    use SoftDeletes;
     protected $guarded = ['id'];

    public function offers()
    {
        return $this->hasMany(Offer::class);

    }

    public function files()
    {
        return $this->morphMany(Media::class, 'model');
    }
    // offerSheets
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }


    public function scopeLive()
    {
        return $this->where('status', 'active');//->where('category_id', '!=', '4');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fees()
    {
        return $this->morphMany(Fee::class,'event');
    }

    public function shippingRegions()
    {
        return $this->morphMany(ShippingRegion::class, 'event');
    }

    public function origins(){

        return $this->belongsToMany(Origin::class,'offer_sheets_origins','offer_sheet_id','origin_id');
    }
}

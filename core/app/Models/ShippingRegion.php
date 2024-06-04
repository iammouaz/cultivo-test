<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRegion extends Model
{
    use SoftDeletes;
    public $table = 'shipping_region';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function event()
    {
        return $this->morphTo('event', 'event_type', 'event_id');
    }

    public function range()//todo remove
    {
        return $this->hasMany(ShippingRanges::class , 'region_id', 'id');
    }
    public function shippingRanges()
    {
        return $this->hasMany(ShippingRanges::class , 'region_id', 'id');
    }

    public function countries()
    {
        return $this->hasMany(ShippingRegionCountry::class,'region_id','id');
    }


}

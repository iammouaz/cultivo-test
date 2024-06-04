<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRegionCountry extends Model
{
    public $table = 'shipping_region_country';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function region()
    {
        return $this->belongsTo(ShippingRegion::class,'id','region_id');
    }


}

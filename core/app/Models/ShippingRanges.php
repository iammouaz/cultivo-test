<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingRanges extends Model
{
    use SoftDeletes;
    public $table = 'shipping_ranges';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function region()
    {
        return $this->hasOne(ShippingRegion::class,'id','region_id');
    }


}

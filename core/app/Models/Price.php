<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory,SoftDeletes;
    protected $appends = ['product_total_price'];
    protected $with = ['size','offer'];
    protected $guarded = ['id'];
    public function offer(){
        return $this->belongsTo(Offer::class);
    }
    public function orders(){
        return $this->belongsToMany(Order::class , 'order_offer_price', 'price_id', 'order_id')->withPivot('quantity');
    }

    public function size(){
        return $this->belongsTo(Size::class, 'size_id');
    }
    public function getProductTotalPriceAttribute()
    {
        if(!$this->size)
            return null;
        return ($this->size->weight_LB * $this->price) + ($this->size->additional_cost??0);
    }
}

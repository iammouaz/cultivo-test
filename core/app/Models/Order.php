<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $appends = ['products'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function getProductsAttribute()
   {
       if(!$this->product_ids){

            return $this->prices;

       }else{

           $product_ids = unserialize( $this->product_ids);
           $products = Product::whereIn('id', $product_ids)->get();
           return $products;
       }
   }
   public function prices()
   {
       return $this->belongsToMany(Price::class, 'order_offer_price', 'order_id', 'price_id')->withPivot('quantity');
   }
   public function sampleSets()
   {
       return $this->belongsToMany(SampleSet::class, 'orders_sample_sets', 'order_id', 'sample_set_id')->withPivot('quantity');
   }
   public function getFullNameAttribute()
   {
       return $this->customer_first_name . ' ' . $this->customer_last_name;
   }
   public function getEmailAttribute()
   {
       return $this->customer_email;
   }

   public function country_shipping(){

    return $this->hasOne(Country::class,'id','shipping_country');

   }

   public function country_billing(){

    return $this->hasOne(Country::class,'id','billing_country');

   }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}

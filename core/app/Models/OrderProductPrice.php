<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductPrice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'order_offer_price';


    public function price(){

        return $this->belongsTo(Price::class);
    }


}

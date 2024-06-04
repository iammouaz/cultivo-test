<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferSpecification extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];


    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}

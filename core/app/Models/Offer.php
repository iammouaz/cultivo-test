<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Size;

class Offer extends Model
{
    use SoftDeletes;
    protected $table = 'offers';
    protected $guarded = ['id'];
    protected $appends = array('photo');



    public function scopeLive()
    {
        return $this->where('status', 1);
    }


    public function prices(){
        return $this->hasMany(Price::class)->has('size');
    }

    public function getPhotoAttribute()
    {
        $image=$this->files()->orderBy('id','desc')->first();
        return isset($image->file_name)?$image->file_name:$this->image;
    }

    public function files()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    public function offerSheet()
    {
        return $this->belongsTo(OfferSheet::class);
    }


    public function offer_specification()
    {
        return $this->hasMany(OfferSpecification::class);
    }


    public function getRankAttribute()
    {
        return Cache::remember('offer_rank_' . $this->id, 5, function () {
            return $this->offer_specification()->where('spec_key', 'Rank')->first('Value')['Value'] ?? false;
        });
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }
}

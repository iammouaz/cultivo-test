<?php

namespace App\Models;


use App\Http\MainApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Event extends Model
{
    // protected $guarded = ['id'];
    protected $fillable = ['end_date','display_end_date', 'start_status'];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'display_end_date' => 'datetime',
        'max_end_date' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);

    }

    public function permissiongroups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'event_permission_group', 'event_id', 'group_id')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_events')->withTimestamps()->groupBy('user_id')->distinct();
    }

    public function permissionuser()
    {
        return $this->belongsToMany(UserPermission::class, 'event_permission_user', 'event_id', 'permission_id')
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function scopeLive()
    {
        return $this->where('status', 'active')->where('practice', '0')->where('category_id', '!=', '4')->where('start_date', '<', now())->where('end_date', '>', now());
    }

    public function scopeUpcoming()
    {
        return $this->where('status', 'active')->where('practice', '0')->where('start_date', '>', now());
    }

    public function scopeMarketPlace()
    {
        return $this->where('status', 'active')->where('practice', '0')->where('category_id', '4')->where('start_date', '<', now())->where('end_date', '>', now());
    }

    public function scopePractice()
    {
        return $this->where('status', 'active')->where('practice', '1')->where('start_date', '<', now())->where('end_date', '>', now());
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fees()
    {
        return $this->morphMany(Fee::class,'event');
    }

    public function getWinningProductsUserIdsAttribute()
    {
        $max_bids = [];
        foreach ($this->products()->get() as $product) {
            if ($product->last_bid_user_id && $product->last_bid_user_id != -1) {
                $max_bids[]['user_id'] = $product->last_bid_user_id;
            }
        }
        return $max_bids;
    }

    public function getUserWinningProductsCountAttribute()
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }


//        $user_ids = $this->getWinningProductsUserIdsAttribute();
//        $user_ids = collect($user_ids);
//
//        $count =  $user_ids->where('user_id','==',$user->id);


        $products = [];
        $is_event_ended = $this->start_status == 'ended';
        if (!$is_event_ended) {
            if (Auth::check()) {
                $productid = get_allowed_products(Auth::id());
                $products = Product::live()->where('event_id', $this->id)->whereIn('id', $productid)->get();
            } else {
                $products = Product::live()->where('event_id', $this->id)->get();
            }
        } else {
            $products = Product::where('event_id', $this->id)->get();
        }

        $count = 0;
        foreach ($products as $product) {
            if (!is_null($product->max_bid()) && $product->max_bid()->user->id == Auth::id()) {
                $count++;
            }
        }
        return $count;
    }

    public function getDateAttribute()
    {
        return Cache::rememberForever('event_date_' . $this->id, function () {
            $start_date = $this->start_date->format('F d');
            $end_date = $this->display_end_date->format('F d');
            if ($start_date == $end_date) {
                $date = $start_date;
            } else {
                $date = $start_date . '  -  ' . $end_date;
            }

            return $date;
        });
    }

    public function shippingRegions()
    {
        return $this->morphMany(ShippingRegion::class, 'event');
    }

    public function sampleSets()
    {
        return $this->hasMany(SampleSet::class);
    }
}

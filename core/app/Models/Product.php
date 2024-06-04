<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $appends = array('total_price', 'weight', 'is_fav', 'last_bid_user_id', 'score_avg', 'logged_in_user_score','grade','origin','final_price','final_total_price');
    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
        'specification' => 'array'
    ];


    // Scope

    public function scopePending()
    {
        return $this->where('status', 0)->where('expired_at', '>', now());
    }

    public function scopeLive()
    {
        return $this->where('status', 1)->where('started_at', '<', now())->where('expired_at', '>', now());
    }

    public function scopeUpcoming()
    {
        return $this->where('status', 1)->where('started_at', '>', now());
    }

    public function scopeExpired()
    {
        return $this->where('expired_at', '<', now());
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function highest_bidder()
    {
        $bid = $this->bids()->orderby('amount', 'desc')->first();
        if (isset($bid)) {
            $user = User::find($bid->user_id);
            return $user->company_name;
        } else {
            return "-";
        }

    }

    public function highest_bidder_id()
    {
        $bid = $this->bids()->orderby('amount', 'desc')->first();

        if (isset($bid)) {

            return $bid->user_id;

        }

        return null;
    }

    public function autobidsettings()
    {
        return $this->hasMany(AutoBidSetting::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }

    public function permissiongroups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'product_permission_group', 'product_id', 'group_id')
            ->withTimestamps();
    }

    public function permissionuser()
    {
        return $this->belongsToMany(UserPermission::class, 'product_permission_user', 'product_id', 'permission_id')
            ->withTimestamps();
    }


    public function max_bid()
    {
        return Cache::remember('event_max_bid_' . $this->id, 5, function () {

            if ($this->bid_count() > 0) {
                // get the highest bid
                $max_bid = $this->bids()->orderBy('amount', 'desc')->with(['user'])->first();
                return $max_bid;
            }

            return null;
        });
    }

    public function count_bids_last1()
    {   // return count bids in the last 2 minute
//        $date = new DateTime();
//        $date->modify('-2 minutes');
//        $formatted_date = $date->format('Y-m-d H:i:s');
//        $count_bids_last1=0;
//        $count_bids_rows = $this->bids()->where('updated_at', '>=', $formatted_date)->get();
//        foreach($count_bids_rows as $one_row){
//            foreach($one_row->user_updated as $val){
//                $val = new DateTime($val);
//                if($date <= $val){
//                    $count_bids_last1 ++;
//                }
//            }
//        }
//        return $count_bids_last1;

        return 1;
    }

    public function bid_count()
    {
        return Cache::remember('product_bid_count_' . $this->id, 5, function () {
            return $this->bids()->count();
        });
    }

    public function product_specification()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function getTotalPriceAttribute()
    {
        return Cache::remember('product_total_price_' . $this->id, 30, function () {

            $w = 1;
            if (isset($this->specification)) {
                foreach ($this->specification as $s) {
                    if ($s['name'] == "Weight" && is_numeric($s['value'])) {
                        if(is_numeric($s['value']))
                            $w = $s['value'];
                        break;
                    }
                }
            }
            return $this->price * $w;
        });
    }

    public function getWeightAttribute()
    {
        return Cache::remember('product_weight_' . $this->id, 30, function () {
            $w = 1;
            if (isset($this->specification)) {
                foreach ($this->specification as $s) {
                    if ($s['name'] == "Weight" && is_numeric($s['value'])) {
                        $w = $s['value'];
                    }
                }
            }
            return $w;
        });
    }
    public function getOriginAttribute()
    {
        return Cache::remember('product_origin_' . $this->id, 30, function () {
            $w = 1;
            if (isset($this->specification)) {
                foreach ($this->specification as $s) {
                    if (strtolower($s['name']) == "origin") {
                        $w = $s['value'];
                        break;
                    }
                }
            }
            return $w;
        });
    }
    public function getGradeAttribute()
    {
        return Cache::remember('product_grade_' . $this->id, 30, function () {
            $w = 1;
            if (isset($this->specification)) {
                foreach ($this->specification as $s) {
                    if (strtolower($s['name']) == "grade") {
                        $w = $s['value'];
                        break;
                    }
                }
            }
            return $w;
        });
    }

    public function getIsFavAttribute()
    {
        $loged_user = auth()->user();
        if (isset($loged_user)) {
            return Cache::remember('product_user_is_fav_' . $this->id . '_user_' . $loged_user->id, 30, function () use ($loged_user) {
                $user_product_fav = new UserProductFav();
                return $user_product_fav->isUserProductFav($loged_user->id, $this->id);
            });
        }
        return false;
    }

    public function getFinalPriceAttribute()
    {
        return Cache::remember('product_final_price_' . $this->id, 5, function () {
            $price = isset($this->max_bid()->amount) ? $this->max_bid()->amount : $this->price;
            return $price;
        });
    }

    public function getFinalTotalPriceAttribute()
    {
        return Cache::remember('product_final_price_total_' . $this->id, 5, function () {
            $price = $this->final_price;
            $weight = $this->weight;
            $total_price = $weight * $price;
            return $total_price;
        });
    }

    public function getWightAttribute()
    {
        return Cache::remember('product_wight_attribute_' . $this->id, 30, function () {

            $weight = 1;
            if (isset($this->product_specification)) {
                foreach ($this->product_specification as $s) {
                    if (strtoupper($s->spec_key) == 'WEIGHT' && is_numeric($s->Value) ) {
                        $weight = $s->Value;
                    }
                }
            }
            return $weight;
        });
    }

    public function getLastBidUserIdAttribute()
    {
        return $this->max_bid() ? $this->max_bid()->user_id : -1;
    }


    public function getScoreAvgAttribute()
    {
        return Cache::remember('product_score_' . $this->id, 5, function () {
            $score = $this->scores()->avg('score');
            return round($score, 2);
        });
    }

    public function getLoggedInUserScoreAttribute()
    {
        $user = auth()->user();
        if (isset($user)) {
            return Cache::remember('product_user_score_' . $this->id . '_user_' . $user->id, 5, function () use ($user) {
                $score = $this->scores()->where('user_id', $user->id)->first('score')['score'] ?? false;
                return $score;
            });
        }

        return false;
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function getRankAttribute()
    {
        return Cache::remember('product_rank_' . $this->id, 5, function () {
            return $this->product_specification()->where('spec_key', 'Rank')->first('Value')['Value'] ?? false;
        });
    }

    public function getIsInAllowListAttribute()
    {
        if (!Auth::check()) {
            return true;
        }
        $productids = get_allowed_products(Auth::id());
        return in_array($this->id, $productids);
    }
}

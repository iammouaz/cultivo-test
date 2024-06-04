<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $data = [
        'data'=>1
    ];




    public function login_logs()
    {
        return $this->hasMany(UserLogin::class);
    }

   public function products()
    {
       return $this->belongsToMany(Product::class,'user_events')
    	->withPivot('event_id','is_active')->withTimestamps();//->where('user_events.is_active',1);
    }

    public function groups()
    {
       return $this->belongsToMany(Group::class,'invitations')
    	->withPivot('status','invitation_type')->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function autobidsettings()
    {
        return $this->hasMany(AutoBidSetting::class);
    }

    public function permissiongroups()
    {
        return $this->belongsToMany(PermissionGroup::class , 'user_permission_group', 'user_id', 'group_id')
        ->withTimestamps();
    }

    public function permissionuser(){
        return $this->belongsTo(UserPermission::class,'user_id','id');
    }

    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }
    public function country(){
        return $this->hasOne(Country::class,'id','billing_country');
    }
    public function country_shipping(){
        return $this->hasOne(Country::class,'id','shipping_country');
    }
    public function userRequestsPendingTermsAccept()
    {
        return $this->hasMany(UserRequest::class,'user_id','id')->where('status',1)->where('terms_accept',0);
    }
    public function userRequestsPendingTermsAcceptArray()
    {
        return $this->userRequestsPendingTermsAccept()->pluck('event_id')->toArray();
    }
    public function userRequestsPendingApproval()
    {
        return $this->hasMany(UserRequest::class,'user_id','id')->where('status',-1);
    }
    public function userRequestsPendingApprovalArray()
    {
        return $this->userRequestsPendingApproval()->pluck('event_id')->toArray();
    }

    public function events(){

        return $this->belongsToMany(Event::class,'user_requests')->where('user_requests.status',1)->withPivot(['date_accept']);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}

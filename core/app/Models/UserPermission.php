<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    public $table = 'user_permission_eve_prod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function event()
    {
        return $this->belongsToMany(Event::class ,'event_permission_user','permission_id','event_id');
    }

    public function user()
    {
        return $this->hasOne(User::class ,'id','user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class , 'product_permission_user', 'permission_id', 'product_id')
        ->withTimestamps();
    }


}

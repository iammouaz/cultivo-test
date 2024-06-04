<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    public $table = 'user_events';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function event()
    {
        return $this->hasOne(Event::class ,'id','event_id');
    }

    public function user()
    {
        return $this->hasOne(User::class ,'id','user_id');
    }

    public function products()
    {
        return $this->hasOne(Product::class);
    }


}

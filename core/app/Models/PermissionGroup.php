<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    public $table = 'permission_group';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsToMany(Event::class,'event_permission_group', 'group_id', 'event_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class , 'user_permission_group', 'group_id', 'user_id')
        ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class , 'product_permission_group', 'group_id', 'product_id')
        ->withTimestamps();
    }

}

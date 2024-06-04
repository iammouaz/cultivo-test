<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    // use HasFactory;
    protected $fillable=['name','leader_id','description','image','event_id'];


    public function leader()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function users()
    {
       return $this->belongsToMany(User::class,'invitations')
    	->withPivot('status','invitation_type')->withTimestamps();
    }
}

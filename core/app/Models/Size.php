<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'sizes';

    protected $fillable = [
        'size',
        'weight_LB',
        'event_id',
        'is_sample',
        'additional_cost'
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function prices()
    {
        return $this->hasMany(Price::class, 'size_id');
    }
}

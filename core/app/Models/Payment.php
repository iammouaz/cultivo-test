<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'payment_type',
        'charge_id',
        'amount',
        'currency',
        'description',
        'event_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}

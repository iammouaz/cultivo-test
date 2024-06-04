<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function scopeActive()
    {
        return $this->where('status', 1);
    }
}

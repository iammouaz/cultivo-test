<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $guarded = ['id'];

    protected $table = 'email_sms_templates';
    protected $fillable=['shortcodes'];
    protected $casts = [
        'shortcodes' => 'object'
    ];

}

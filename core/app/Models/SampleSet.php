<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'price',
        'total_package_weight_Lb',
        'number_of_samples_per_box',
        'weight_per_sample_grams',
        'image',
    ];
    protected $table = 'sample_sets';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}

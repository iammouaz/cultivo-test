<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    public $table = 'product_specification';
    protected $guarded = ['id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductFav extends Model
{
    use HasFactory;
    protected $table = 'user_product_favs';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function toggleUserProductFav($user_id, $product_id): bool
    {
        $user_product_fav = $this->where('user_id', $user_id)->where('product_id', $product_id)->first();
        if ($user_product_fav) {
            $user_product_fav->delete();
            return false;
        } else {
            $this->create([
                'user_id' => $user_id,
                'product_id' => $product_id,
            ]);
            return true;
        }
    }

    public function isUserProductFav($user_id, $product_id)
    {
        $user_product_fav = $this->where('user_id', $user_id)->where('product_id', $product_id)->first();
        return $user_product_fav ? true : false;
    }
}

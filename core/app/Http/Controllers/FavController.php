<?php

namespace App\Http\Controllers;

use App\Models\UserProductFav;
use Illuminate\Http\Request;

class FavController extends Controller
{
    public function toggleFav(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);
        $loged_user = auth()->user();
        $user_product_fav = new UserProductFav();

        $is_add =  $user_product_fav->toggleUserProductFav($loged_user->id, $validated['product_id']);
        flush_favorite_products_cache($validated['product_id'], $loged_user->id);
        return response()->json([
            'success' => true,
            'is_add' => $is_add,
        ]);
    }
}

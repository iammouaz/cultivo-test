<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\Request;

class UserScoreController extends Controller
{
    public function submitScore(Request $request){
        $validatedData = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'score' => 'required|min:1|max:100',
        ]);

       $score = Score::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $validatedData['product_id'],
        ], [
            'score' => $validatedData['score'],
        ]);

       $score->update([
           'score' => $validatedData['score']]);

        return response()->json([
            'message' => 'Score submitted successfully',
            'score' => round($validatedData['score'],2)
        ]);
    }
}

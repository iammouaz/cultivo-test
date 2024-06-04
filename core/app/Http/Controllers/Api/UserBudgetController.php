<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class UserBudgetController extends Controller
{

    public function submitBudget(Request $request){
        $validatedData = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'budget' => 'required|gt:0',
        ]);

        $budget = Budget::firstOrCreate([
            'user_id' => auth()->id(),
            'event_id' => $validatedData['event_id'],
        ], [
            'budget' => $validatedData['budget'],
        ]);

        $budget->update([
            'budget' => $validatedData['budget']]);

        return response()->json([
            'message' => 'Budget submitted successfully',
            'budget' => round($validatedData['budget'],2)
        ]);
    }
}

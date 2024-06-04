<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EcommerceLoginController extends Controller
{

    public function check_user (Request $request) {
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'is_email' => 'required'
        ]);

        if($validatedData['is_email'] == 1) {
            $user = User::where('email', $validatedData['username'])->first();
        }else{
            $user = User::where('username', $validatedData['username'])->first();
        }

        if (!$user) {
            return response([ 'errors' => array('The Email Or Username Not Exist')],404);
        }

        $is_true = Hash::check($validatedData['password'], $user->password);


        if (!$is_true) {
            return response([ 'errors' => array('The Password Is Wrong')],404);
        }

        return response()->json([
            'user' => $user
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();
            return $user->makeVisible('api_token');
        }

        return response()->json([
            'message' => 'email or password incorrect'
        ], 401);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->api_token = null;
            $user->save();

            return response()->json([
                'message' => 'See you next time'
            ], 200);
        }

        return response()->json([
            'message' => 'Unable to logout user'
        ], 401);
    }

    public function user(Request $request)
    {
        return Auth::user()->makeVisible(['api_token']);
    }
}

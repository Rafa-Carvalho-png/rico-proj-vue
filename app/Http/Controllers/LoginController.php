<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)){
            abort(401, 'Unauthorized');
        }

        return response()->json([
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'token_expires' => Auth::factory()->getTTL() * 60
            ]
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}

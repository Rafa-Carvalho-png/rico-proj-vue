<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request, User $user)
    {
        $credentials = $request->only(['name', 'email', 'password']);
        $credentials['password'] = bcrypt($credentials['password']);

        if (!$user = $user->create($credentials)){
            abort(500, 'Something went wrong');
        }

        return response()->json([
            'data' => [
                'user' => $user
            ]
        ]);
    }
}

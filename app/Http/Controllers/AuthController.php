<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)){
            abort(401, 'Unauthorized');
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_expires' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function register(RegisterRequest $request, User $user): JsonResponse
    {
        $credentials = $request->only(['name', 'email', 'password']);
        $credentials['password'] = bcrypt($credentials['password']);

        if (!$user = $user->create($credentials)){
            abort(500, 'Something went wrong');
        }

        $token = auth()->attempt($request->only(['email', 'password']));
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'token_expires' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function forgotPassword(): JsonResponse
    {
        return response()->json([
            'message' => 'Password reset email sent'
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

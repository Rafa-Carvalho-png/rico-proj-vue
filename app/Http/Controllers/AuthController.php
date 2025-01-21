<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendForgotPasswordEmail;
use App\Http\Requests\RegisterRequest;

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

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        SendForgotPasswordEmail::dispatch($request->email, $token);

        return response()->json([
            'message' => 'Password reset email sent'
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $passwordReset->email)->delete();

        return response()->json(['message' => 'Password has been reset successfully']);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

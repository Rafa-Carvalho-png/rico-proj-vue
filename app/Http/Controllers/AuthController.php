<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendForgotPasswordEmail;
use App\Http\Requests\RegisterRequest;
use App\Services\PasswordResetService;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService,
        private PasswordResetService $passwordResetService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)){
            abort(401, 'Unauthorized');
        }

        $this->userService->setOnline(auth()->id());
        return response()->json([
            'id' => auth()->id(),
            'token' => $token,
            'token_type' => 'bearer',
            'token_expires' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->only(['name', 'email', 'password']);
        $credentials['password'] = $this->userService->encryptPassword($credentials['password']);

        if (!$user = $this->userService->insert($credentials)){
            abort(500, 'Something went wrong');
        }

        $token = auth()->attempt($request->only(['email', 'password']));
        $this->userService->setOnline(auth()->id());
        return response()->json([
            'id' => auth()->id(),
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'token_expires' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $token = $this->passwordResetService->resetToken($request->email);
        if (empty($token)) {
            return response()->json([
                'error' => 'Failed to generate token'
            ], 500);
        }

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

        $passwordReset = $this->passwordResetService
            ->findByToken($request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $this->userService->resetPassword($passwordReset->email, $request->password);
        $this->passwordResetService->deleteByEmail($passwordReset->email);

        return response()->json(['message' => 'Password has been reset successfully']);
    }

    public function logout()
    {
        $this->userService->setOffline(auth()->id());
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}

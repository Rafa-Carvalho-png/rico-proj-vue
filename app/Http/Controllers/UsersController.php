<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function getOnline(): JsonResponse
    {
        $users = $this->userService->getOnlineUsers();
        return response()->json($users);
    }
}

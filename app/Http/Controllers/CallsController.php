<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CallService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CallsController extends Controller
{
    public function __construct(
        private CallService $callService,
    ) {
    }

    public function paginateUserCalls(Request $request)
    {
        $userId = Auth::id();
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 15);

        $calls = $this->callService->paginateUserCalls($page, $perPage, $userId);

        return response()->json($calls);
    }

}

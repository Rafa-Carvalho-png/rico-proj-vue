<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CallService;
use App\Services\UserService;
use App\Events\IncomingCallEvent;
use App\Events\ResponseCallEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\CommunicationIntegrator\CommunicationIntegratorInterface;

class CallsController extends Controller
{
    public function __construct(
        private CallService $callService,
        private CommunicationIntegratorInterface $twilioService,
        private UserService $userService,
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

    public function requestCall(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        broadcast(new IncomingCallEvent(auth()->id(), $request->user_id));

        return response()->json(['message' => 'Call request sent.']);
    }

    public function acceptCall(Request $request): JsonResponse
    {
        $request->validate([
            'caller_id'   => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $caller   = $this->userService->find($request->caller_id);
        $receiver = $this->userService->find($request->receiver_id);

        // $twilioCall = $this->twilioService->makeCallBetweenUsers($caller, $receiver);
        $twilioCall = (object) ['sid' => 'fake-sid'];
        $this->callService->insertInProgressCall(
            $request->caller_id,
            $request->receiver_id,
            $twilioCall->sid
        );
        broadcast(new ResponseCallEvent($request->caller_id, $request->receiver_id, true));

        return response()->json([
            'success' => true,
            'call' => $twilioCall,
        ]);
    }

    public function rejectCall(Request $request): JsonResponse
    {
        $request->validate([
            'caller_id'   => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        broadcast(new ResponseCallEvent($request->caller_id, $request->receiver_id, false));
        $this->callService->insertRejectedCall($request->caller_id, $request->receiver_id);

        return response()->json(['message' => 'Call rejected.']);
    }
}

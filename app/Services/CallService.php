<?php

namespace App\Services;

use App\Repository\Call\CallRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CallService
{
    public function __construct(
        private CallRepositoryInterface $callRepository,
    ) {
    }

    public function paginateUserCalls(int $page, int $perPage, int $userId): LengthAwarePaginator
    {
        return $this->callRepository->paginateUserCalls($page, $perPage, $userId);
    }

    public function insertInProgressCall($callerId, $receiverId, $callSid): void
    {
        $this->callRepository->insert([
            'from_user' => $callerId,
            'to_user' => $receiverId,
            'call_sid' => $callSid,
            'status' => 'in_progress',
        ]);
    }

    public function insertRejectedCall($callerId, $receiverId): void
    {
        $this->callRepository->insert([
            'from_user' => $callerId,
            'to_user' => $receiverId,
            'status' => 'rejected',
        ]);
    }
}

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
}

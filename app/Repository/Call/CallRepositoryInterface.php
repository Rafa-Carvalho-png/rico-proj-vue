<?php

namespace App\Repository\Call;

use App\Repository\AbstractRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface CallRepositoryInterface extends AbstractRepositoryInterface
{
    public function paginateUserCalls(int $page, int $perPage, int $userId): LengthAwarePaginator;
}

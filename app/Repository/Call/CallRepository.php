<?php

namespace App\Repository\Call;

use App\Models\Call;
use App\Repository\AbstractRepository;
use App\Repository\Call\CallRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CallRepository extends AbstractRepository implements CallRepositoryInterface
{
    public function __construct(Call $model)
    {
        parent::__construct($model);
    }

    public function paginateUserCalls(int $page = 1, int $perPage = 10, int $userId): LengthAwarePaginator
    {
        return Call::where('from_user', $userId)
            ->orWhere('to_user', $userId)
            ->paginate($perPage, ['*'], 'page', $page);
    }
}

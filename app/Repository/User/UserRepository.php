<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\AbstractRepository;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getOnlineUsers(): Collection
    {
        return $this->model->where('is_online', true)->get();
    }
}

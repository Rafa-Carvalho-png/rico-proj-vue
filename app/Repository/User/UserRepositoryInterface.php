<?php

namespace App\Repository\User;

use Illuminate\Support\Collection;
use App\Repository\AbstractRepositoryInterface;

interface UserRepositoryInterface extends AbstractRepositoryInterface
{
    public function getOnlineUsers(): Collection;
}

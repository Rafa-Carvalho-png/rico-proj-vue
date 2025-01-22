<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\AbstractRepository;
use App\Repository\User\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}

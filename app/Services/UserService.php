<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repository\User\UserRepositoryInterface;

class UserService
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function insert(array $data): User
    {
        return $this->userRepository->insert($data);
    }

    public function resetPassword(string $email, string $password): bool
    {
        $user = $this->userRepository
            ->filter(['email' => $email])
            ->first();

        if (!$user) {
            abort(['error' => 'User not found'], 404);
        }

        return $this->userRepository->update($user->id, ['password' => $this->encryptPassword($password)]);
    }

    public function encryptPassword(string $password): string
    {
        return Hash::make($password);
    }
}

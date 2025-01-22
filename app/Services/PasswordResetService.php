<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Repository\PasswordReset\PasswordResetRepositoryInterface;

class PasswordResetService
{
    public function __construct(
        private PasswordResetRepositoryInterface $passwordResetRepository
    ) {
    }

    public function findByToken(string $token): Collection
    {
        return $this->passwordResetRepository->filter(['token' => $token]);
    }

    public function findByEmail(string $email): Collection
    {
        return $this->passwordResetRepository->filter(['email' => $email]);
    }

    public function resetToken(string $email): string
    {
        $token = Str::random(60);
        $resetPasswordToken = $this->passwordResetRepository->updateOrInsert($email, $token);
        return $resetPasswordToken ? $token : '';
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->passwordResetRepository->deleteByEmail($email);
    }
}

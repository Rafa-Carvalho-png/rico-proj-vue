<?php

namespace App\Repository\PasswordReset;

use App\Models\PasswordReset;
use App\Repository\AbstractRepositoryInterface;

interface PasswordResetRepositoryInterface extends AbstractRepositoryInterface
{
    public function validateTokenExpiration(string $token, PasswordReset $passwordReset): bool;
    public function updateOrInsert(string $email, string $token): bool;
}

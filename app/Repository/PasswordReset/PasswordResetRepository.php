<?php

namespace App\Repository\PasswordReset;

use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Repository\AbstractRepository;
use App\Repository\PasswordReset\PasswordResetRepositoryInterface;

class PasswordResetRepository extends AbstractRepository implements PasswordResetRepositoryInterface
{
    public function __construct(PasswordReset $passwordReset)
    {
        parent::__construct($passwordReset);
    }

    public function validateTokenExpiration(string $token, PasswordReset $passwordReset): bool
    {
        if (!$passwordReset) {
            abort(404, 'Token not found');
        }

        return now() > $passwordReset->created_at->addMinutes(60);
    }

    public function updateOrInsert(string $email, string $token): bool
    {
        $passwordReset = $this->model->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        return $passwordReset ? true : false;
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->model::where('email', $email)->delete();
    }

}

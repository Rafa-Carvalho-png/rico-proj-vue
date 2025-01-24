<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repository\User\UserRepositoryInterface;
use App\Services\CommunicationIntegrator\CommunicationIntegratorInterface;

class UserService
{

    public function __construct(
        private CommunicationIntegratorInterface $twilioService,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function insert(array $userData): User
    {
        // $subaccount = $this->twilioService->createSubaccountWithNumber($userData['email']);
        // $userData['twilio_sid'] = $subaccount['sid'];
        // $userData['twilio_auth_token'] = $subaccount['authToken'];
        // $userData['phone_number'] = $subaccount['phoneNumber'];

        return $this->userRepository->insert($userData);
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

    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function getOnlineUsers()
    {
        $users = $this->userRepository->getOnlineUsers();
        return $users->where('id', '!=', auth()->id());
    }

    public function encryptPassword(string $password): string
    {
        return Hash::make($password);
    }

    public function setOnline(int $id): bool
    {
        return $this->userRepository->update($id, ['is_online' => true]);
    }

    public function setOffline(int $id): bool
    {
        return $this->userRepository->update($id, ['is_online' => false]);
    }
}

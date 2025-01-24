<?php

namespace App\Services\CommunicationIntegrator;

use App\Models\User;

interface CommunicationIntegratorInterface
{
    public function createSubaccountWithNumber(string $email): array;
    public function updateSubaccountStatus(string $subaccountSid, string $status): void;
    public function generateAccessToken(string $subaccountSid, string $subaccountAuthToken):object;
    public function makeCallBetweenUsers(User $caller, User $receiver):object;
    public function subscribeNumber(object $subaccount, string $countryCode = 'US', array $phoneOptions = []): ?object;
}

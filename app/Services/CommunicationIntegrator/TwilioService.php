<?php

namespace App\Services\CommunicationIntegrator;

use App\Models\User;
use Twilio\Rest\Client;

class TwilioService implements CommunicationIntegratorInterface
{
    protected $client;

    public function __construct($sid, $authToken)
    {
        $this->client = new Client($sid, $authToken);
    }

    public function createSubaccountWithNumber(string $email): array
    {
        $friendlyName = explode("@", $email)[0];
        $subaccount = $this->client->api->v2010->accounts->create([
            'friendlyName' => $friendlyName,
        ]);

        $incomingNumber = $this->subscribeNumber($subaccount, 'BR', [
            'areaCode' => '14',
        ]);

        return [
            'sid'         => $subaccount->sid,
            'authToken'   => $subaccount->authToken,
            'phoneNumber' => $incomingNumber->phoneNumber,
            'status'      => $subaccount->status,
        ];
    }

    public function subscribeNumber(object $subaccount, string $countryCode = 'US', array $phoneOptions = []): ?object
    {
        $subClient = new Client($subaccount->sid, $subaccount->authToken);

        $availableNumbers = $subClient->availablePhoneNumbers($countryCode)
                                     ->local
                                     ->read($phoneOptions, 1);

        if (empty($availableNumbers)) {
            return null;
        }

        $chosenNumber = $availableNumbers[0]->phoneNumber;

        return $subClient->incomingPhoneNumbers->create([
            'phoneNumber' => $chosenNumber,
            'friendlyName' => $subaccount->friendlyName . " Number",
        ]);
    }


    public function updateSubaccountStatus(string $subaccountSid, string $status): void
    {
        $this->client->accounts($subaccountSid)->update([
            'status' => $status,
        ]);
    }

    public function generateAccessToken(string $subaccountSid, string $subaccountAuthToken): object
    {
        $client = new Client($subaccountSid, $subaccountAuthToken);
        return $client->tokens->create();
    }

    public function makeCallBetweenUsers(User $caller, User $receiver): object
    {
        $client = new Client($caller->twilio_sid, $caller->twilio_auth_token);

        return $client->calls->create(
            $receiver->phone_number,
            $caller->phone_number,
            ['url' => 'https://popular-camel-touching.ngrok-free.app/twiml/connect-call']
        );
    }
}

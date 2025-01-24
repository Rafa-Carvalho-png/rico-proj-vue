<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class IncomingCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $caller_id;
    public $receiver_id;

    public function __construct($caller_id, $receiver_id)
    {
        $this->caller_id = $caller_id;
        $this->receiver_id = $receiver_id;
    }

    public function broadcastOn()
    {
        return new Channel('calls');
    }

    public function broadcastAs()
    {
        return 'IncomingCall_' . $this->receiver_id;
    }

    public function braodcastWith()
    {
        return [
            'caller_id' => $this->caller_id,
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ResponseCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $caller_id;
    public $receiver_id;
    public $accepted;

    public function __construct($caller_id, $receiver_id, $accepted)
    {
        $this->caller_id = $caller_id;
        $this->receiver_id = $receiver_id;
        $this->accepted = $accepted;
    }

    public function broadcastOn()
    {
        return new Channel('calls');
    }

    public function broadcastAs()
    {
        return 'ResponseCall_' . $this->caller_id;
    }

    public function braodcastWith()
    {
        return [
            'caller_id' => $this->receiver_id,
            'accepted' => $this->accepted,
        ];
    }
}

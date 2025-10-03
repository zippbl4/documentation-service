<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets; 
    use SerializesModels;

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }

    public function broadcastWith()
    {
        return ['message' => 'Hello from Laravel!'];
    }
}

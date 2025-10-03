<?php

namespace App\Broadcasting;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::extend(SocketIoBroadcaster::getName(), function ($app, $config) {
            return new SocketIoBroadcaster();
        });
    }
}

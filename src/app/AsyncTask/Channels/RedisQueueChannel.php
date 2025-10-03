<?php

namespace App\AsyncTask\Channels;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class RedisQueueChannel implements ChannelContract
{
    private string $channel;
    private string $readyToCloseChannel;
    private int $timeout = 10;

    public function open(string $channel): void
    {
        $this->channel = $channel;
        $this->readyToCloseChannel = "$channel-ready-to-close";
    }

    public function isOpen(): bool
    {
        return Redis::exists($this->channel) > 0;
    }

    public function close(): void
    {
        Redis::del($this->channel);
        Redis::del($this->readyToCloseChannel);
    }

    public function readyToClose(): void
    {
        Redis::rpush($this->readyToCloseChannel, 1);
    }

    public function isReadyToClose(): bool
    {
        return Redis::exists($this->readyToCloseChannel) > 0;
    }

    public function send(string $data): void
    {
        Redis::rpush($this->channel, $data);
    }

    public function receive(): string
    {
        return Redis::lpop($this->channel);
    }

    public function receiveAll(): \Generator
    {
        $time = Carbon::now()->addSeconds($this->timeout);
        while (true) {
            switch (true) {
                case $data = $this->receive():
                    yield $data;
                    $time = Carbon::now()->addSeconds($this->timeout);
                    break;
                case Carbon::now()->greaterThan($time):
                case $this->isReadyToClose():
                    $this->close();
                    return;
            }
        }
    }

    public function isEmpty(): bool
    {
        return Redis::llen($this->channel) === 0;
    }
}

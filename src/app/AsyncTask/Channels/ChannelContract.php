<?php

namespace App\AsyncTask\Channels;

interface ChannelContract
{
    public function open(string $channel): void;
    public function close(): void;
    public function readyToClose(): void;
    public function send(string $data): void;
    public function receive(): string;
    public function receiveAll(): \Generator;
}

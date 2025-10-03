<?php

namespace App\AsyncTask\Contracts\Handlers;

interface ReceiveHandler
{
    public function receive(string $data): void;
}

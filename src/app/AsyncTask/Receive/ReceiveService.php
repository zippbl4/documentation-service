<?php

namespace App\AsyncTask\Receive;

use App\AsyncTask\Contracts\Handlers\ReceiveHandler;
use App\AsyncTask\Services\BaseService;

final class ReceiveService extends BaseService
{
    public function receiveFromChannel(string $name, ReceiveHandler $handler): void
    {
        $channel = $this->getChannel();
        $channel->open($name);
        foreach ($channel->receiveAll() as $data) {
            $handler->receive($data);
        }
        $channel->close();
    }

    public function flush(ReceiveHandler $handler): void
    {
        if (method_exists($handler, 'flush')) {
            $handler->flush();
        }
    }
}

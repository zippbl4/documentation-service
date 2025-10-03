<?php

namespace App\AsyncTask\Send;

use App\AsyncTask\Contracts\Handlers\SenderHandler;
use App\AsyncTask\Services\BaseService;

final class SendService extends BaseService
{
    public function sendToChannel(string $name, SenderHandler $handler): void
    {
        $channel = $this->getChannel();
        $channel->open($name);
        $items = $handler->data();

        if ($items === null) {
            $channel->close();
            return;
        }

        foreach ($items as $item) {
            $channel->send($item);
        }

        $channel->readyToClose();
    }
}

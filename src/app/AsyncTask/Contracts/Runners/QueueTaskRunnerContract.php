<?php

namespace App\AsyncTask\Contracts\Runners;

use App\AsyncTask\Contracts\Handlers\ReceiveHandler;
use App\AsyncTask\Contracts\Handlers\SenderHandler;

interface QueueTaskRunnerContract
{
    /**
     * @param array<class-string<SenderHandler>>|class-string<SenderHandler> $writers
     * @param array<class-string<ReceiveHandler>>|class-string<ReceiveHandler> $readers
     * @return void
     */
    public function run(array|string $writers, array|string $readers): void;
}

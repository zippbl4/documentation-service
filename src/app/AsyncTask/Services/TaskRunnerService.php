<?php

namespace App\AsyncTask\Services;

use App\AsyncTask\Receive\ReceiveFromChannelJob;
use App\AsyncTask\Send\SendToChannelJob;
use Illuminate\Contracts\Bus\Dispatcher;
use Ramsey\Uuid\Uuid;

final class TaskRunnerService
{
    public function __construct(private Dispatcher $dispatcher)
    {
    }

    public function run(string $channelClass, array|string $writers, array|string $readers): void
    {
        $channelName = Uuid::uuid4()->toString();

        if (is_string($writers)) {
            $writers = [$writers];
        }

        if (is_string($readers)) {
            $readers = [$readers];
        }

        foreach ($writers as $writer) {
            $this->dispatcher->dispatch(new SendToChannelJob(
                channelClass: $channelClass,
                channelName: $channelName,
                handler: $writer,
            ));
        }

        foreach ($readers as $reader) {
            $this->dispatcher->dispatch(new ReceiveFromChannelJob(
                channelClass: $channelClass,
                channelName: $channelName,
                handler: $reader,
            ));
        }
    }
}

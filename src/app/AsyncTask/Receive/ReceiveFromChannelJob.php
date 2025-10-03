<?php

namespace App\AsyncTask\Receive;

use App\AsyncTask\Channels\ChannelContract;
use App\AsyncTask\Contracts\Handlers\ReceiveHandler;
use App\AsyncTask\Services\HandlerClassString;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReceiveFromChannelJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        /** @var class-string<ChannelContract> */
        public string $channelClass,
        public string $channelName,
        /** @var class-string<ReceiveHandler:{json}> */
        public string $handler,
    ) {
    }

    public function displayName(): string
    {
        return sprintf(
            '%s (%s)',
            self::class,
            $this->handler,
        );
    }

    public function handle(ReceiveService $service, HandlerClassString $handlerClassString, Container $container): void
    {
        [$handler, $parameters] = $handlerClassString->parseHandlerString($this->handler);

        $handler = $container->make($handler, $parameters);
        $channel = $container->make($this->channelClass);

        $service
            ->setChannel($channel)
            ->receiveFromChannel($this->channelName, $handler)
        ;
        $service->flush($handler);
    }
}

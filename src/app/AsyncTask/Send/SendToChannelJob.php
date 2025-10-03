<?php

namespace App\AsyncTask\Send;

use App\AsyncTask\Channels\ChannelContract;
use App\AsyncTask\Contracts\Handlers\SenderHandler;
use App\AsyncTask\Services\HandlerClassString;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendToChannelJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        /** @var class-string<ChannelContract> */
        public string $channelClass,
        public string $channelName,
        /** @var class-string<SenderHandler:{json}> */
        public string $handler,
    ) {
    }

    public function displayName(): string
    {
        return sprintf(
            '%s(%s)',
            self::class,
            $this->handler,
        );
    }

    public function handle(SendService $service, HandlerClassString $handlerClassString, Container $container): void
    {
        [$handler, $parameters] = $handlerClassString->parseHandlerString($this->handler);

        $handler = $container->make($handler, $parameters);
        $channel = $container->make($this->channelClass);

        $service
            ->setChannel($channel)
            ->sendToChannel($this->channelName, $handler)
        ;
    }
}

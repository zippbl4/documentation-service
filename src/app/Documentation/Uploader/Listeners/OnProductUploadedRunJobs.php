<?php

namespace App\Documentation\Uploader\Listeners;

use App\AsyncTask\Contracts\HandlerClassStringContract;
use App\AsyncTask\Contracts\Runners\PubSubTaskRunnerContract;
use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;

class OnProductUploadedRunJobs
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private LoggerInterface            $logger,
        private PubSubTaskRunnerContract   $runner,
        private HandlerClassStringContract $classStringContract,
        private array                      $writers,
        private array                      $readers,
    ) {
    }

    public function handle(ProductUploaded $event): void
    {
        $this->runner->run(
            $this->getHandlers($this->writers, $event->toArray()),
            $this->getHandlers($this->readers, $event->toArray()),
        );
    }

    private function getHandlers(array $handlers, array $params): array
    {
        return array_map(fn (string $item): string => $this->classStringContract->createHandlerString($item, $params), $handlers);
    }
}

<?php

namespace App\Archive\Unpacker\Decorators;

use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Archive\Unpacker\Events\ArchiveUnpacked;
use App\Archive\Unpacker\Exceptions\UnpackerException;
use App\Contracts\NameInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Psr\Log\LoggerInterface;

final readonly class DispatchEventDecoratorUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public function __construct(
        private UnpackerStrategy $strategy,
        private LoggerInterface $logger,
        private Dispatcher $dispatcher,
    ) {
    }

    public static function getName(): string
    {
        throw new UnpackerException();
    }

    // TODO магия?
    public function unpack(string $trgArchive, string $dstDir): void
    {
        $baseStrategyName = $this->strategy->getName();

        $this->logger->info('Decorated {strategy}:start:unpack', ['strategy' => $baseStrategyName]);
        $this->strategy->unpack($trgArchive, $dstDir);
        $this->logger->info('Decorator {strategy}:end:unpack', ['strategy' => $baseStrategyName]);

        $this->logger->info('Decorator {strategy}:start-event', ['strategy' => $baseStrategyName]);
        $this->dispatcher->dispatch(new ArchiveUnpacked(
            trgArchive: $trgArchive,
            dstDir: $dstDir,
        ));
        $this->logger->info('Decorator {strategy}:end-event', ['strategy' => $baseStrategyName]);
    }
}

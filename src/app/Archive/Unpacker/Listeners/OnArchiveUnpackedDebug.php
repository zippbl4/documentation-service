<?php

namespace App\Archive\Unpacker\Listeners;

use App\Archive\Unpacker\Events\ArchiveUnpacked;
use Psr\Log\LoggerInterface;

final readonly class OnArchiveUnpackedDebug
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function handle(ArchiveUnpacked $event): void
    {
        $this->logger->debug('Unpacked: ArchiveUnpackedEvent');
    }
}

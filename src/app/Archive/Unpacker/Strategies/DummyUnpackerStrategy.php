<?php

namespace App\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Contracts\NameInterface;

final readonly class DummyUnpackerStrategy extends BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public static function getName(): string
    {
        return 'dummy';
    }

    /**
     * @inheritDoc
     */
    public function unpack(string $trgArchive, string $dstDir): void
    {
        $this->logger->info('DummyUnpackerStrategy:in:out');
    }
}

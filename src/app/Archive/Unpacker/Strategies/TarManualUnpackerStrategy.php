<?php

namespace App\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Contracts\NameInterface;

final readonly class TarManualUnpackerStrategy extends BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public static function getName(): string
    {
        return 'tar_manual';
    }

    public function unpack(string $trgArchive, string $dstDir): void
    {
        $this->logger->info("[tar_manual] Archive: $trgArchive, to: $dstDir");

        exec("tar -xvzf $trgArchive -C $dstDir", $result);
        $this->logger->info("[tar_manual] tar –xvzf $trgArchive –C $dstDir");
        $this->logger->info("[tar_manual] result:", $result);

        exec("chmod -R 775 $dstDir");
        $this->logger->info('[tar_manual] success');
    }
}

<?php

namespace App\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Contracts\NameInterface;

final readonly class GZipManualUnpackerStrategy extends BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public static function getName(): string
    {
        return 'gzip_manual';
    }

    public function unpack(string $trgArchive, string $dstDir): void
    {
        $this->logger->info("[gzip_manual] Archive: $trgArchive, to: $dstDir");

        exec("gunzip $trgArchive -d $dstDir", $result);
        $this->logger->info("[gzip_manual] gunzip $trgArchive -d $dstDir", $result);

        exec("chmod -R 775 $dstDir");
        $this->logger->info('[gzip_manual] success');
    }
}

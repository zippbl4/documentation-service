<?php

namespace App\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Contracts\NameInterface;

final readonly class ZipManualUnpackerStrategy extends BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public static function getName(): string
    {
        return 'zip_manual';
    }

    public function unpack(string $trgArchive, string $dstDir): void
    {
        $this->logger->info("[zip_manual] Archive: $trgArchive, to: $dstDir");

        exec("unzip $trgArchive -d $dstDir", $result);
        $this->logger->info('[zip_manual] unzip result', $result);

        exec("chmod -R 775 $dstDir");
        $this->logger->info('[zip_manual] success');
    }
}

<?php

namespace App\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Archive\Unpacker\Exceptions\UnpackerException;
use App\Contracts\NameInterface;

final readonly class ZipUnpackerStrategy extends BaseUnpackerStrategy implements UnpackerStrategy, NameInterface
{
    public static function getName(): string
    {
        return 'zip';
    }

    /**
     * @inheritDoc
     */
    public function unpack(string $trgArchive, string $dstDir): void
    {
        $this->logger->info("[zip] Archive: $trgArchive, to: $dstDir");

        $zip = new \ZipArchive();
        if ($zip->open($trgArchive) !== true) {
            throw new UnpackerException('Can\'t open file: ' . $trgArchive);
        }

        $zip->extractTo($dstDir);
        $zip->close();
    }
}

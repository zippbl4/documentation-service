<?php

namespace App\Archive\Unpacker\Events;

class ArchiveUnpacked
{
    public function __construct(
        public string $trgArchive,
        public string $dstDir,
    ) {
        //
    }
}

<?php

namespace App\Archive\Unpacker\Contracts;

use App\Archive\Unpacker\Exceptions\UnpackerException;

interface UnpackerStrategy
{
    /**
     * @param string $trgArchive
     * @param string $dstDir
     * @return void
     * @throws UnpackerException
     */
    public function unpack(string $trgArchive, string $dstDir): void;
}

<?php

namespace App\Documentation\Uploader\Managers;

interface ArchiveUploaderJobManagerInterface
{
    public function createJob(
        int $aspectId,
        string $archiveHash,
        string $unpackerStrategy,
        string $archivePath,
    ): void;
}

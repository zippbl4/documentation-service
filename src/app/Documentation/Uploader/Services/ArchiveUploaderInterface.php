<?php

namespace App\Documentation\Uploader\Services;

use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Documentation\Uploader\DTO\UploadedFileDTO;
use SplFileInfo;

interface ArchiveUploaderInterface
{
    public function moveReleaseArchive(
        UploadedFileDTO $releaseArchive,
        string          $archivesDir
    ): SplFileInfo;

    public function removeReleaseArchive(
        string $releaseArchivePath,
    ): void;

    public function unpackReleaseArchive(
        UnpackerStrategy $unpacker,
        UploadedFileDTO  $releaseArchive,
        string           $releasesDir,
    ): void;
}

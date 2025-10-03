<?php

namespace App\Documentation\Uploader\Services;

use App\Archive\Unpacker\Contracts\UnpackerStrategy;
use App\Documentation\Uploader\DTO\UploadedFileDTO;
use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use SplFileInfo;

final readonly class ArchiveUploaderService implements ArchiveUploaderInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private Filesystem $filesystem,
    ) {
    }

    public function moveReleaseArchive(
        UploadedFileDTO $releaseArchive,
        string          $archivesDir
    ): SplFileInfo {
        $this->filesystem->move(
            $releaseArchive->getPathname(),
            $archivesDir . $releaseArchive->getClientName()
        );

        $this->logger->debug(
            sprintf(
                "Archive %s moved from %s to %s.",
                $releaseArchive->getName(),
                $releaseArchive->getPathname(),
                $archivesDir . $releaseArchive->getClientName(),
            )
        );

        return new SplFileInfo($archivesDir . $releaseArchive->getClientName());
    }

    public function removeReleaseArchive(
        string $releaseArchivePath,
    ): void {
        $this->filesystem->delete($releaseArchivePath);

        $this->logger->debug(
            sprintf(
                "Archive %s deleted.",
                $releaseArchivePath,
            )
        );
    }

    public function unpackReleaseArchive(
        UnpackerStrategy $unpacker,
        UploadedFileDTO  $releaseArchive,
        string           $releasesDir,
    ): void {
        $unpacker->unpack(
            $releaseArchive->getPathname(),
            $releasesDir,
        );
    }
}

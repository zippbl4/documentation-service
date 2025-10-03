<?php

namespace App\Documentation\Uploader\Jobs;

use App\Documentation\Uploader\Managers\ArchiveUploaderJobManager;
use App\Documentation\Uploader\Managers\ArchiveUploaderJobManagerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class ArchiveUploaderJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    private ArchiveUploaderJobManagerInterface $archiveUploaderManagement;

    public function __construct(
        public int $aspectId,
        public string $archiveHash,
        public string $unpackerStrategy,
        public string $archivePath,
        public string $uuid,
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(ArchiveUploaderJobManagerInterface $archiveUploaderManagement): void
    {
        $archiveUploaderManagement->run($this);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $e): void
    {
        $this->archiveUploaderManagement->failed(
            $this,
            $e,
        );
    }

    public function setManager(ArchiveUploaderJobManagerInterface $archiveUploaderManagement): self
    {
        $this->archiveUploaderManagement = $archiveUploaderManagement;
        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

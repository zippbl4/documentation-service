<?php

namespace App\Watcher\Directory\Jobs;

use App\Watcher\Directory\Services\DirectoryWatcherJobManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DirectoryWatcherJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        public string $uuid,
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(DirectoryWatcherJobManager $watcherJobManager): void
    {
        $watcherJobManager->run($this);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $e): void
    {
        DirectoryWatcherJobManager::failed(
            $this,
            $e,
        );
    }
}

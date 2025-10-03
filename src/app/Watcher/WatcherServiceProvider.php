<?php

namespace App\Watcher;

use App\Watcher\Directory\Contracts\DirectoryWatcherJobManagerInterface;
use App\Watcher\Directory\Services\DirectoryWatcherJobManager;
use Illuminate\Log\Logger;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class WatcherServiceProvider extends ServiceProvider
{
    public function boot(DirectoryWatcherJobManagerInterface $directoryWatcherJobManager): void
    {
        $directoryWatcherJobManager->createJob();
    }

    public function register(): void
    {
        $this
            ->app
            ->when(DirectoryWatcherJobManagerInterface::class)
            ->needs(LoggerInterface::class)
            ->give(Logger::class);

        $this->app->singleton(DirectoryWatcherJobManagerInterface::class, DirectoryWatcherJobManager::class);

        $this->app->afterResolving(DirectoryWatcherJobManager::class, function (DirectoryWatcherJobManager $manager): void {
            // Наркоман?
            $manager::setInstance($manager);
        });
    }
}

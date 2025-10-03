<?php

namespace App\Watcher\Directory\Services;

use App\Config\DTO\ConfigDTO;
use App\Config\Enums\FeatureFlagEnum;
use App\User\Contracts\UserNotificationServiceInterface;
use App\Watcher\Directory\Contracts\DirectoryWatcherJobManagerInterface;
use App\Watcher\Directory\Events\DiscoveredNewFoldersEvent;
use App\Watcher\Directory\Jobs\DirectoryWatcherJob;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\Dispatcher as JobDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Laravel\Nova\Notifications\NovaNotification;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;
use Throwable;

class DirectoryWatcherJobManager implements DirectoryWatcherJobManagerInterface
{
    private const LOCK = 'DirectoryWatcherService.lock';
    private const PREV_STATE = 'DirectoryWatcherService.state';
    private const DELAY = 1 * 10;
    private static ?self $instance = null;

    public function __construct(
        private LoggerInterface $logger,
        private FilesystemManager $filesystem,
        private JobDispatcher $jobDispatcher,
        private EventDispatcher $eventDispatcher,
        private ConfigDTO $config,
        private UserNotificationServiceInterface $notificationService,
    ) {
    }

    public function createJob(): void
    {
        if ($this->config->watcherDirectoryFeatureFlag === FeatureFlagEnum::Disabled) {
            $this->unlock();
            return;
        }

        if ($this->isLocked()) {
            return;
        }

        $this->createJobManually();

        $this->lock();
    }

    private function createJobManually(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $this->logger->info(
            sprintf(
                "Job[%s]: %s dispatching...\nDelay: %s",
                $uuid,
                'DirectoryWatcherJob',
                static::DELAY,
            )
        );

        $this->jobDispatcher->dispatch(
            (new DirectoryWatcherJob($uuid))->delay(self::DELAY),
        );
    }

    public function run(DirectoryWatcherJob $job): void
    {
        $uuid = $job->uuid;

        $this->logger->info(
            sprintf(
                "Job[%s]: %s starting...\nDelay: %s",
                $uuid,
                'DirectoryWatcherJob',
                static::DELAY,
            )
        );

        // TODO переделать
        if (count($this->getDisk('uploader')->allFiles()) > 0) {
            $this->logger->info(
                sprintf(
                    "Job[%s]: %s waiting another jobs. Jobs: \n%s",
                    $uuid,
                    'DirectoryWatcherJob',
                    json_encode($this->getDisk('uploader')->allFiles(), flags: JSON_PRETTY_PRINT),
                )
            );

            $this->createJobManually();
            return;
        }

        $directories = (new Finder())
            ->in($this->config->releaseFolder)
            ->directories()
            ->depth(0)
            ->notName([
                '__MACOSX',
            ])
        ;

        $directories = iterator_to_array($directories);
        $directories = array_keys($directories);
        $exists = $this->getPrevState();

        $discoveredDirectories = array_diff($directories, $exists);
        if (! empty($discoveredDirectories)) {
            $this->eventDispatcher->dispatch(new DiscoveredNewFoldersEvent(
                $discoveredDirectories
            ));

            $this->setPrevState($directories);
        }

        $this->logger->info(
            sprintf(
                "Job[%s]: %s end.",
                $uuid,
                'DirectoryWatcherJob',
            )
        );

        $this->createJobManually();
    }

    public static function setInstance(self $instance): void
    {
        static::$instance = $instance;
    }

    private static function i(): self
    {
        return static::$instance;
    }

    public static function failed(DirectoryWatcherJob $job, Throwable $e): void
    {
        $self = self::i();

        $uuid = $job->uuid;

        $self->logger->info(
            sprintf(
                "Job[%s]: %s failed. Unlock. Send notification. Recreate Job. \n%s",
                $uuid,
                'DirectoryWatcherJob',
                $e->getMessage(),
            )
        );

        $self->notificationService->sendNotificationToAdmins(
            NovaNotification::make()
                ->message("Процесс DirectoryWatcherJob[$uuid] обнаружения новых релизов упал!")
                ->icon('error')
                ->type('error')
        );

        $self->createJobManually();
    }

    public function lock(): void
    {
        if ($this->isLocked()) {
            return;
        }

        $this->getDisk()->put(self::LOCK, Carbon::now());
    }

    public function unlock(): void
    {
        if (! $this->isLocked()) {
            return;
        }

        $this->getDisk()->delete(self::LOCK);
    }

    public function isLocked(): bool
    {
        return $this->getDisk()->exists(self::LOCK);
    }

    private function getDisk(string $disk = 'watcher'): Filesystem
    {
        return $this->filesystem->disk($disk);
    }

    private function setPrevState(array $directories): void
    {
        $data = json_encode(
            $directories,
            flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        $this->getDisk()->put(self::PREV_STATE, $data);
    }

    private function getPrevState(): array
    {
        return json_decode(
            $this->getDisk()->get(self::PREV_STATE),
            true,
        ) ?? [];
    }
}

<?php

namespace App\Documentation\Uploader\Managers;

use App\Archive\Researcher\Contracts\ArchiveResearcherFactoryInterface;
use App\Archive\Unpacker\Contracts\UnpackerFactoryInterface;
use App\Archive\Validation\Contracts\ArchiveValidatorInterface;
use App\Archive\Validation\DTO\ContextDTO;
use App\Archive\Validation\Rules\RootFolderExists;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Uploader\Events\ProductUploaded;
use App\Documentation\Uploader\Jobs\ArchiveUploaderJob;
use App\Documentation\Uploader\Services\ArchiveUploaderInterface;
use App\User\Contracts\UserNotificationServiceInterface;
use Illuminate\Contracts\Bus\Dispatcher as JobDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Filesystem\Filesystem;
use Laravel\Nova\Notifications\NovaNotification;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

final class ArchiveUploaderJobManager implements ArchiveUploaderJobManagerInterface
{
    public const DELAY = 1 * 0;

    public function __construct(
        private LoggerInterface                   $logger,
        private ArchiveUploaderInterface          $archiveUploader,
        private UnpackerFactoryInterface          $unpackerFactory,
        private ArchiveValidatorInterface         $validator,
        private ArchiveResearcherFactoryInterface $researcherFactory,
        private JobDispatcher                     $jobDispatcher,
        private EventDispatcher                   $eventDispatcher,
        private Filesystem                        $filesystem,
        private UserNotificationServiceInterface  $notificationService,
        private AspectServiceContract             $aspectService,
    ) {
    }

    public function createJob(
        int $aspectId,
        string $archiveHash,
        string $unpackerStrategy,
        string $archivePath,
    ): void {
        $job = new ArchiveUploaderJob(
            $aspectId,
            $archiveHash,
            $unpackerStrategy,
            $archivePath,
            Uuid::uuid4()->toString(),
        );

        $this->logger->debug("ArchiveUploaderJobManager: dispatching.", $job->toArray());

        $this->jobDispatcher->dispatch(
            $job->delay(static::DELAY),
        );
    }

    public function run(ArchiveUploaderJob $job): void
    {
        $aspect = $this->aspectService->getAspect($job->aspectId);
        $pathPatternRegex = $aspect->getPathPatternRegexFilledByAspect();

        $this->logger->debug("ArchiveUploaderJobManager: starting.", ['releasesDir' => $aspect->path->getRoot()] + $job->toArray());

        try {
            if (! $this->filesystem->exists($job->archivePath)) {
                $this->logger->debug("ArchiveUploaderJobManager: archive not found.", $job->toArray());

                return;
            }

            $ctx = (new ContextDTO())
                ->withValue('unpackStrategy', $job->unpackerStrategy)
                ->withValue('archivePath', $job->archivePath)
                ->withValue('pathPattern', $pathPatternRegex)
            ;

            $archiveValidator = $this
                ->validator
                ->make($ctx, [
                    new RootFolderExists(),
                ])
            ;

            if ($archiveValidator->fails()) {
                $this->logger->debug("ArchiveUploaderJobManager: validator fails.", $job->toArray() + ['validator' => $archiveValidator->getMessages()]);

                return;
            }

            $archiveRootFolder = $this
                ->researcherFactory
                ->get($job->unpackerStrategy)
                ->getRootFolder($job->archivePath)
            ;

            if ($this->filesystem->exists($aspect->path->getRoot() . $archiveRootFolder)) {
                $this->logger->debug("ArchiveUploaderJobManager: product already exists.", $job->toArray());

                return;
            }

            $this
                ->unpackerFactory
                ->get($job->unpackerStrategy)
                ->unpack(
                    $job->archivePath,
                    $aspect->path->getRoot(),
                );

            $this->eventDispatcher->dispatch(
                new ProductUploaded(
                    aspectId: $job->aspectId,
                    jobUuid: $job->uuid,
                    productPath: $aspect->path->getRoot() . $archiveRootFolder,
                ),
            );

            $this->logger->debug("ArchiveUploaderJobManager: end.", $job->toArray());
        } finally {
            $this->archiveUploader->removeReleaseArchive(
                $job->archivePath,
            );

            $this->logger->debug("ArchiveUploaderJobManager: archive removed.", $job->toArray());
        }
    }

    public function failed(ArchiveUploaderJob $job, Throwable $e): void
    {
        $uuid = $job->uuid;

        $this->logger->debug("ArchiveUploaderJobManager: failed.", $job->toArray() + ['error' => $e->getMessage()]);

        $this->notificationService->sendNotificationToAdmins(
            NovaNotification::make()
                ->message("Процесс ArchiveUploaderJob[$uuid] загрузки новых релизов упал!")
                ->icon('error')
                ->type('error')
        );
    }
}

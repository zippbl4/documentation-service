<?php

namespace Unit\Archive\Uploader\Managers;

use App\Archive\Unpacker\Contracts\UnpackerFactoryInterface;
use App\Archive\Unpacker\Strategies\DummyUnpackerStrategy;
use App\Archive\Uploader\Contracts\ArchiveUploaderInterface;
use App\Archive\Uploader\Contracts\ArchiveUploaderJobManagerInterface;
use App\Archive\Uploader\Jobs\ArchiveUploaderJob;
use Illuminate\Contracts\Bus\Dispatcher as JobDispatcher;
use Illuminate\Queue\WorkerOptions;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ArchiveUploaderJobManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateJob(): void
    {
        $params = [
            1,
            'hash',
            'dummy',
            $this->app->storagePath('/tests/tmp-archive-' . microtime(true) . '/'),
            $this->app->storagePath('/tests/tmp-releases-' . microtime(true) . '/'),
            Uuid::uuid4()->toString(),
        ];

        $mock = $this->mock(JobDispatcher::class, function (MockInterface $mock) use ($params) {
            $mock
                ->shouldReceive('dispatch')
                ->once()
            ;
        });

        $this->app->instance(
            JobDispatcher::class,
            $mock,
        );

        $this
            ->app
            ->make(ArchiveUploaderJobManagerInterface::class)
            ->createJob(...$params)
        ;
    }

    public function testRun(): void
    {
        $mock = $this->partialMock(ArchiveUploaderInterface::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('removeReleaseArchive')
                ->once()
            ;
        });

        $this->app->instance(
            ArchiveUploaderInterface::class,
            $mock,
        );

        $mock = $this->mock(UnpackerFactoryInterface::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('get')
                ->andReturn($this->app->make(DummyUnpackerStrategy::class))
                ->once()
            ;
        });

        $this->app->instance(
            UnpackerFactoryInterface::class,
            $mock,
        );

        $params = [
            1,
            'hash',
            'zip_manual',
            $this->app->storagePath('/tests/zip/R2018b.zip'),
            $this->app->storagePath('/tests/tmp-releases-' . microtime(true) . '/'),
            Uuid::uuid4()->toString(),
        ];

        $this
            ->app
            ->make(JobDispatcher::class)
            ->dispatch(new ArchiveUploaderJob(...$params))
        ;

        $this
            ->app
            ->make('queue.worker')
            ->setName('default')
            ->setCache($this->app->make(\Illuminate\Contracts\Cache\Repository::class))
            ->runNextJob(
                'database',
                'default',
                new WorkerOptions(),
            )
        ;
    }
}

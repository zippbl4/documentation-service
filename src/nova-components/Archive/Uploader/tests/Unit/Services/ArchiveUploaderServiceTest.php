<?php

namespace Tests\Unit\Services;

use App\Archive\Unpacker\Events\ArchiveUnpacked;
use App\Archive\Unpacker\Strategies\DummyUnpackerStrategy;
use App\Archive\Uploader\Contracts\ArchiveUploaderInterface;
use App\Archive\Uploader\Contracts\UploadedFileDTOConverterInterface;
use App\Mutex\Contracts\MutexInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;


class ArchiveUploaderServiceTest extends TestCase
{
    /**
     * @covers \App\Archive\Uploader\Services\ArchiveUploaderUploader::unpackReleaseArchive
     */
    public function testUnpackReleaseArchive()
    {
        $this->app->instance(
            Filesystem::class,
            Mockery::mock(Filesystem::class, function (MockInterface $mock) {
                $mock->shouldReceive('delete')->andReturn(true);
            })
        );

        $this->app->instance(
            MutexInterface::class,
            Mockery::mock(MutexInterface::class, function (MockInterface $mock) {
                $mock->shouldReceive('lock')->andReturn(true);
                $mock->shouldReceive('unlock');
            })
        );

        $this->app->instance(
            Dispatcher::class,
            Mockery::mock(Dispatcher::class, function (MockInterface $mock) {
                $mock->shouldReceive('dispatch')->with(ArchiveUnpacked::class);
            })
        );

        $zip = new \SplFileObject(app()->storagePath('/tests/zip/symfony-docs-6.4.zip'));

        $releaseArchiveService = $this->app->make(ArchiveUploaderInterface::class);
        $releaseArchiveService->unpackReleaseArchive(
            $this->app->make(DummyUnpackerStrategy::class),
            $this->app->make(UploadedFileDTOConverterInterface::class)->buildBySplFile($zip),
            app()->storagePath('/tests/release')
        );
    }
}

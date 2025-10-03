<?php

namespace Tests\Unit\Archive\Unpacker\Strategies;

use App\Archive\Unpacker\Strategies\ZipManualUnpackerStrategy;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\NullLogger;
use Tests\TestCase;

class ZipManualUnpackerStrategyTest extends TestCase
{
    private string $tmpPath;
    private string $zipPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tmpPath = $this->app->storagePath('/tests/tmp' . microtime(true) . '/');

        if (! is_dir($this->tmpPath)) {
            mkdir($this->tmpPath);
        }

        $this->zipPath = $this->app->storagePath('/tests/zip/');
    }

    protected function tearDown(): void
    {
        $this->app->make(Filesystem::class)->deleteDirectory($this->tmpPath);

        parent::tearDown();
    }

    #[DataProvider(methodName: 'data')]
    public function testUnpack(string $archive, array $expected): void
    {
        $strategy = new ZipManualUnpackerStrategy();
        $strategy->setLogger(new NullLogger());

        $strategy->unpack(
            $this->zipPath . $archive,
            $this->tmpPath,
        );

        foreach ($expected as $file) {
            $this->assertFileExists($this->tmpPath . $file);
        }
    }

    public static function data(): iterable
    {
        yield [
            'archive' => 'without_root.zip',
            'expected' => [
                'auth.php',
                'pagination.php',
                'passwords.php',
                'validation.php',
            ],
        ];

        yield [
            'archive' => 'with_root.zip',
            'expected' => [
                'with_root/auth.php',
                'with_root/pagination.php',
                'with_root/passwords.php',
                'with_root/validation.php',
            ],
        ];
    }
}

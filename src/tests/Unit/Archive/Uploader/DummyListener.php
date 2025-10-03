<?php

namespace Tests\Unit\Archive\Uploader;

use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DummyListener
{
    use InteractsWithQueue;
    use Queueable;

    private string $handler;

    public function __construct(
    ) {
    }

    public function handle($event): void
    {
        dd($event);
    }

    public function failed(ProductUploaded $event, Throwable $e): void
    {

    }

    public function setHandler(string $handler): void
    {
        $this->handler = $handler;
    }
}

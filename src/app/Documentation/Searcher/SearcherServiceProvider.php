<?php

namespace App\Documentation\Searcher;

use App\Documentation\Searcher\Listeners\IndexerHandler;
use App\Documentation\Searcher\Listeners\OnProductUploadedRunIndexerJob;
use App\Documentation\Uploader\Events\ProductUploaded;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Log\LoggerInterface;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerContextualBinding();
    }

    public function boot(Dispatcher $events): void
    {
        $this->bootEvents($events);
    }

    public function bootEvents(Dispatcher $events): void
    {
    }

    public function registerContextualBinding(): void
    {
        $needs = LoggerInterface::class;
        $logger = static function ($app): LoggerInterface {
            return (new LogManager($app))->channel('uploader');
        };


        $this
            ->app
            ->when(OnProductUploadedRunIndexerJob::class)
            ->needs($needs)
            ->give($logger);

        $this
            ->app
            ->when(IndexerHandler::class)
            ->needs($needs)
            ->give($logger);

    }
}

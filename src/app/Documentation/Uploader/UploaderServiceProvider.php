<?php

namespace App\Documentation\Uploader;

use App\Archive\Uploader\ArchiveUploaderTool;
use App\AsyncTask\Contracts\WorkerFactoryContract;
use App\Dictionary\AliasDictionary;
use App\Documentation\Uploader\Converters\UploadedFileDTOConverter;
use App\Documentation\Uploader\Converters\UploadedFileDTOConverterInterface;
use App\Documentation\Uploader\Events\ProductUploaded;
use App\Documentation\Uploader\Handlers\ProductUploadedSenderHandler;
use App\Documentation\Uploader\Http\Middleware\Authorize;
use App\Documentation\Uploader\Listeners\OnProductUploadedRunJobs;
use App\Documentation\Uploader\Managers\ArchiveUploaderJobManager;
use App\Documentation\Uploader\Managers\ArchiveUploaderJobManagerInterface;
use App\Documentation\Uploader\Services\ArchiveUploaderInterface;
use App\Documentation\Uploader\Services\ArchiveUploaderService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Psr\Log\LoggerInterface;

class UploaderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerServices();
        $this->registerContextualBinding();
        $this->registerProductUploadedSenderHandlers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events): void
    {
        $this->bootRoutes();
        $this->bootNova();
        $this->bootEvents($events);
    }

    public function bootNova(): void
    {
        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    public function bootRoutes(): void
    {
        $this->app->booted(function () {
            if ($this->app->routesAreCached()) {
                return;
            }

            Nova::router(['nova', Authenticate::class, Authorize::class], ArchiveUploaderTool::NAME)
                ->group(ArchiveUploaderTool::getToolPath() . '/../routes/inertia.php');

            Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/' . ArchiveUploaderTool::NAME)
                ->group(ArchiveUploaderTool::getToolPath() . '/../routes/api.php');
        });
    }

    public function bootEvents(Dispatcher $events): void
    {
        $events->listen(ProductUploaded::class, [OnProductUploadedRunJobs::class, 'handle']);
    }

    public function registerServices(): void
    {
        $this->app->bind(UploadedFileDTOConverterInterface::class, UploadedFileDTOConverter::class);
        $this->app->bind(ArchiveUploaderInterface::class, ArchiveUploaderService::class);
        $this->app->bind(ArchiveUploaderJobManagerInterface::class, ArchiveUploaderJobManager::class);
    }

    public function registerProductUploadedSenderHandlers(): void
    {
        $this->app->extend(WorkerFactoryContract::class, function (WorkerFactoryContract $factory): WorkerFactoryContract {
            $factory->add(AliasDictionary::PRODUCT_UPLOADED_SENDER_HANDLERS, ProductUploadedSenderHandler::class);
            return $factory;
        });
    }

    public function registerContextualBinding(): void
    {
        $needs = LoggerInterface::class;
        $logger = static function ($app): LoggerInterface {
            return (new LogManager($app))->channel('uploader');
        };

        $this
            ->app
            ->when(ArchiveUploaderJobManager::class)
            ->needs($needs)
            ->give($logger);

        $this
            ->app
            ->when(ArchiveUploaderService::class)
            ->needs($needs)
            ->give($logger);

        $this
            ->app
            ->when(OnProductUploadedRunJobs::class)
            ->needs('$writers')
            ->give(fn (Container $app): array => $app->make(WorkerFactoryContract::class)->get(AliasDictionary::PRODUCT_UPLOADED_SENDER_HANDLERS));

        $this
            ->app
            ->when(OnProductUploadedRunJobs::class)
            ->needs('$readers')
            ->give(fn (Container $app): array => $app->make(WorkerFactoryContract::class)->get(AliasDictionary::PRODUCT_UPLOADED_RECEIVER_HANDLERS));
    }
}

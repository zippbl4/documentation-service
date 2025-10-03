<?php

namespace App\Documentation\AspectPlugin;

use App\AsyncTask\Contracts\WorkerFactoryContract;
use App\Dictionary\AliasDictionary;
use App\Documentation\Aspect\Entities\Aspect;
use App\Documentation\AspectPlugin\PageDriver\Drivers\EloquentDriver;
use App\Documentation\AspectPlugin\PageDriver\Handlers\MongoSaverReceiverHandler;
use App\Documentation\AspectPlugin\PageDriver\Handlers\MysqlSaverReceiverHandler;
use App\Documentation\AspectPlugin\PageDriver\Repositories\MongoPageRepository;
use App\Documentation\AspectPlugin\PageDriver\Repositories\MysqlPageRepository;
use App\Documentation\AspectPlugin\PageDriver\Repositories\PageRepositoryInterface;
use App\Documentation\AspectPlugin\Product\ProductEntity;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Log\LoggerInterface;

class AspectPluginServiceProvider extends BaseServiceProvider
{
    public function boot(Dispatcher $events): void
    {
        $this->bootEvents($events);
    }

    public function bootEvents(Dispatcher $events): void
    {
    }

    public function register(): void
    {
        $this->app->bind(PageRepositoryInterface::class, MysqlPageRepository::class);

        $this->registerPageDriver();
        $this->registerContextualBinding();
        $this->registerDynamicRelations();
        $this->registerProductUploadedReceiverHandlers();
    }

    public function registerPageDriver(): void
    {
        $this->app->extend(AliasDictionary::DRIVERS, function ($manager) {
            $manager->addDriver($this->app->make(EloquentDriver::class));
            return $manager;
        });
    }

    public function registerContextualBinding(): void
    {
        $needs = LoggerInterface::class;
        $logger = static function ($app): LoggerInterface {
            return (new LogManager($app))->channel('uploader');
        };
    }

    public function registerDynamicRelations(): void
    {
        Aspect::resolveRelationUsing('products', function (Aspect $model) {
            return $model->hasMany(ProductEntity::class);
        });
    }

    public function registerProductUploadedReceiverHandlers(): void
    {
        $this->app->extend(WorkerFactoryContract::class, function (WorkerFactoryContract $factory): WorkerFactoryContract {
            $factory->add(AliasDictionary::PRODUCT_UPLOADED_RECEIVER_HANDLERS, MysqlSaverReceiverHandler::class);
            $factory->add(AliasDictionary::PRODUCT_UPLOADED_RECEIVER_HANDLERS, MongoSaverReceiverHandler::class);
            return $factory;
        });
    }
}

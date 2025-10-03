<?php

namespace App\Archive\Unpacker;

use App\Archive\Unpacker\Contracts\BaseUnpackerStrategy;
use App\Archive\Unpacker\Contracts\UnpackerFactoryInterface;
use App\Archive\Unpacker\Contracts\UnpackerSupportedStrategiesInterface;
use App\Archive\Unpacker\Events\ArchiveUnpacked;
use App\Archive\Unpacker\Factories\Factory;
use App\Archive\Unpacker\Listeners\OnArchiveUnpackedDebug;
use App\Archive\Unpacker\Strategies\DummyUnpackerStrategy;
use App\Archive\Unpacker\Strategies\GZipManualUnpackerStrategy;
use App\Archive\Unpacker\Strategies\TarManualUnpackerStrategy;
use App\Archive\Unpacker\Strategies\ZipManualUnpackerStrategy;
use App\Archive\Unpacker\Strategies\ZipUnpackerStrategy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Log\Logger;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Log\LoggerInterface;

class UnpackerServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerEventsContextualBinding();

        $this->app->afterResolving(BaseUnpackerStrategy::class, function (BaseUnpackerStrategy $strategy): void {
            $strategy->setLogger((new LogManager($this->app))->channel('unpacker'));
        });

        $this->app->tag(array_filter([
            $this->app->isLocal() ? DummyUnpackerStrategy::class : null,
            ZipUnpackerStrategy::class,
            ZipManualUnpackerStrategy::class,
            TarManualUnpackerStrategy::class,
            GZipManualUnpackerStrategy::class,
        ]), 'unpackers');

//        TODO инстанцировать декораторы не выходит
//        $this->app->tag([
//            DispatchEventDecoratorUnpackerStrategy::class,
//        ], 'unpackers.decorators');

        $this->app->singleton(UnpackerFactoryInterface::class, Factory::class);
        $this->app->singleton(UnpackerSupportedStrategiesInterface::class, Factory::class);

        $this->app->extend(Factory::class, function (Factory $manager): Factory {
            foreach ($this->app->tagged('unpackers') as $strategy) {
                $manager->add($strategy::getName(), $strategy);
            }
            return $manager;
        });
    }

    public function boot(Dispatcher $events): void
    {
        $this->bootEvents($events);
    }

    public function bootEvents(Dispatcher $events): void
    {
        if ($this->app->isLocal()) {
            $events->listen(ArchiveUnpacked::class, [OnArchiveUnpackedDebug::class, 'handle']);
        }
    }

    public function registerEventsContextualBinding(): void
    {
        $this
            ->app
            ->when(OnArchiveUnpackedDebug::class)
            ->needs(LoggerInterface::class)
            ->give(Logger::class);
    }
}

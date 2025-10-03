<?php

namespace App\Page\Driver;

use App\Dictionary\AliasDictionary;
use App\Page\Driver\Contracts\DriverFactoryInterface;
use App\Page\Driver\Contracts\SupportedDriversInterface;
use App\Page\Driver\Drivers\LocalDriver;
use App\Page\Driver\Drivers\RemoteDriver;
use App\Page\Driver\Factories\DriverFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class DriverServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerFactory();
        $this->registerDrivers();
    }

    public function registerFactory(): void
    {
        $this->app->alias(DriverFactoryInterface::class, AliasDictionary::DRIVERS);
        $this->app->singleton(DriverFactoryInterface::class, DriverFactory::class);
        $this->app->singleton(SupportedDriversInterface::class, AliasDictionary::DRIVERS);
    }

    public function registerDrivers(): void
    {
        $this->app->extend(AliasDictionary::DRIVERS, function (DriverFactory $manager): DriverFactory {
            $manager->addDriver($this->app->make(LocalDriver::class));
            $manager->addDriver($this->app->make(RemoteDriver::class));
            return $manager;
        });
    }
}

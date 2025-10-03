<?php

namespace App\Archive\Researcher;

use App\Archive\Researcher\Contracts\ArchiveResearcherFactoryInterface;
use App\Archive\Researcher\Factories\Factory;
use App\Archive\Researcher\Strategies\ZipManualResearcherStrategy;
use Illuminate\Support\ServiceProvider;

class ResearcherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerResearcherStrategies();
    }

    public function registerResearcherStrategies(): void
    {
        $this->app->singleton(ArchiveResearcherFactoryInterface::class, Factory::class);

        $this->app->extend(Factory::class, function (Factory $factory): Factory {
            $factory->add(ZipManualResearcherStrategy::getName(), $this->app->make(ZipManualResearcherStrategy::class));

            return $factory;
        });
    }
}

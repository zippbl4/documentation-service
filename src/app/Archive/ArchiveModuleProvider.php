<?php

namespace App\Archive;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ArchiveModuleProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->registerProviders();
    }

    public function registerProviders(): void
    {
        foreach ($this->getProviders() as $provider) {
            $this->app->register($provider);
        }
    }

    public function getProviders(): array
    {
        return [
            \App\Archive\Researcher\ResearcherServiceProvider::class,
            \App\Archive\Validation\ValidationServiceProvider::class,
            \App\Archive\Unpacker\UnpackerServiceProvider::class,
        ];
    }
}

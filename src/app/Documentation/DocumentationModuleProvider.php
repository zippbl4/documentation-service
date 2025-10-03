<?php

namespace App\Documentation;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class DocumentationModuleProvider extends BaseServiceProvider
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
            \App\Documentation\Diff\DiffServiceProvider::class,
            \App\Documentation\Aspect\AspectServiceProvider::class,
            \App\Documentation\AspectPlugin\AspectPluginServiceProvider::class,
            \App\Documentation\Access\AccessServiceProvider::class,
            \App\Documentation\Viewer\ViewerServiceProvider::class,
            \App\Documentation\Correction\CorrectionsServiceProvider::class,
            \App\Documentation\Uploader\UploaderServiceProvider::class,
            \App\Documentation\Researcher\ResearcherServiceProvider::class,
        ];
    }
}

<?php

namespace App\Documentation\Aspect;

use App\Documentation\Aspect\Contracts\AspectConverterInterface;
use App\Documentation\Aspect\Contracts\AspectRepositoryContract;
use App\Documentation\Aspect\Contracts\AspectServiceContract;
use App\Documentation\Aspect\Converters\AspectConverter;
use App\Documentation\Aspect\Events\AspectConfigSaved;
use App\Documentation\Aspect\Events\AspectCreated;
use App\Documentation\Aspect\Events\PathSaved;
use App\Documentation\Aspect\Listeners\OnAspectConfigSavedUpdateSettings;
use App\Documentation\Aspect\Listeners\OnAspectCreatedCreateDefaultSettings;
use App\Documentation\Aspect\Listeners\OnPathSavedCreateNginxAspect;
use App\Documentation\Aspect\Repositories\AspectRepository;
use App\Documentation\Aspect\Services\AspectService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class AspectServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AspectRepositoryContract::class, AspectRepository::class);
        $this->app->singleton(AspectConverterInterface::class, AspectConverter::class);
        $this->app->singleton(AspectServiceContract::class, AspectService::class);
    }

    public function boot(Dispatcher $events): void
    {
        $this->bootEvent($events);
    }

    public function bootEvent(Dispatcher $events): void
    {
        $events->listen(PathSaved::class, [OnPathSavedCreateNginxAspect::class, 'handle']);
        $events->listen(AspectCreated::class, [OnAspectCreatedCreateDefaultSettings::class, 'handle']);
        $events->listen(AspectConfigSaved::class, [OnAspectConfigSavedUpdateSettings::class, 'handle']);
    }
}

<?php

namespace App\Documentation\Researcher;

use App\Documentation\Researcher\Contracts\ResearcherServiceInterface;
use App\Documentation\Researcher\Services\ResearcherService;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * @deprecated
 */
class ResearcherServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ResearcherServiceInterface::class, ResearcherService::class);

        $this->registerContextualBinding();
    }

    public function registerContextualBinding(): void
    {
        $needs = LoggerInterface::class;
        $logger = static function ($app): LoggerInterface {
            return (new LogManager($app))->channel('uploader');
        };

        $this
            ->app
            ->when(ResearcherService::class)
            ->needs($needs)
            ->give($logger);
    }
}
